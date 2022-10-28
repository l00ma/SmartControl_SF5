<?php

namespace App\Controller;

use App\Entity\Security;
use App\Repository\MembersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\VarExporter\Internal\Values;

class MainController extends AbstractController
{

    /**
     * @var MembersRepository;
     */
    private $repository;

    public function __construct(MembersRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        $request = Request::createFromGlobals();
        $this->request = $request;
    }

    #[Route('/welcome', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('main/welcome.html.twig');
    }

    #[Route('/welcome/load', name: 'welcome_load')]
    public function w_load(ManagerRegistry $doctrine): JsonResponse
    {
        $etat = $this->getUser()->getLedsStrip()->getEtat();
        $temp_int = $this->getUser()->getLedsStrip()->getTemp();
        $temp_ext = $this->getUser()->getLedsStrip()->getTempExt();
        $temp_bas = $this->getUser()->getLedsStrip()->getTempBas();

        $enreg = $this->getUser()->getMouvementPir()->getEnreg();
        $enreg_detect = $this->getUser()->getMouvementPir()->getEnregDetect();
        $alert = $this->getUser()->getMouvementPir()->getAlert();

        $pression = $this->getUser()->getMeteo()->getPression();
        $vitesse_vent = $this->getUser()->getMeteo()->getVitesseVent();
        $direction_vent = $this->getUser()->getMeteo()->getDirectionVent();
        $location = $this->getUser()->getMeteo()->getLocation();
        $humidite = $this->getUser()->getMeteo()->getHumidite();
        $weather = $this->getUser()->getMeteo()->getWeather();
        $icon_id = $this->getUser()->getMeteo()->getIconId();
        $leve_soleil = $this->getUser()->getMeteo()->getLeveSoleil();
        $couche_soleil = $this->getUser()->getMeteo()->getCoucheSoleil();
        $temp_f1 = $this->getUser()->getMeteo()->getTempF1();
        $temp_f2 = $this->getUser()->getMeteo()->getTempF2();
        $temp_f3 = $this->getUser()->getMeteo()->getTempF3();
        $time_f1 = $this->getUser()->getMeteo()->getTimeF1();
        $time_f2 = $this->getUser()->getMeteo()->getTimeF2();
        $time_f3 = $this->getUser()->getMeteo()->getTimeF3();
        $weather_f1 = $this->getUser()->getMeteo()->getWeatherF1();
        $weather_f2 = $this->getUser()->getMeteo()->getWeatherF2();
        $weather_f3 = $this->getUser()->getMeteo()->getWeatherF3();
        $icon_f1 = $this->getUser()->getMeteo()->getIconF1();
        $icon_f2 = $this->getUser()->getMeteo()->getIconF2();
        $icon_f3 = $this->getUser()->getMeteo()->getIconF3();

        $security_rep = $doctrine->getRepository(Security::class);

        $films = $emails = array_fill(0, 5, '0');
        $i = $j = 0;

        foreach ($security_rep->findByMedia(8) as $key => $val) {
            $timeStamp = $val["time_stamp"]->format('\l\e d/m/Y à H:i:s');
            $films[$i] = $timeStamp;
            $i++;
        }
        foreach ($security_rep->findByMedia(2) as $key => $val) {
            $timeStamp = $val["time_stamp"]->format('\l\e d/m/Y à H:i:s');
            $emails[$j] = $timeStamp;
            $j++;
        }

        return $this->json(['etat' => $etat, 'temp_int' => $temp_int, 'temp_ext' => $temp_ext, 'temp_bas' => $temp_bas, 'enreg' => $enreg, 'enreg_detect' => $enreg_detect, 'alert' => $alert, 'pression' => $pression, 'vitesse_vent' => $vitesse_vent, 'direction_vent' => $direction_vent, 'location' => $location, 'humidite' => $humidite, 'weather' => $weather, 'icon_id' => $icon_id, 'leve_soleil' => $leve_soleil, 'couche_soleil' => $couche_soleil, 'temp_f1' => $temp_f1, 'temp_f2' => $temp_f2, 'temp_f3' => $temp_f3, 'time_f1' => $time_f1, 'time_f2' => $time_f2, 'time_f3' => $time_f3, 'weather_f1' => $weather_f1, 'weather_f2' => $weather_f2, 'weather_f3' => $weather_f3, 'icon_f1' => $icon_f1, 'icon_f2' => $icon_f2, 'icon_f3' => $icon_f3, '0' => $films[0], '1' => $films[1], '2' => $films[2], '3' => $films[3], '4' => $films[4], '5' => $emails[0], '6' => $emails[1], '7' => $emails[2], '8' => $emails[3], '9' => $emails[4]]);
    }

    #[Route('/motion', name: 'motion')]
    public function motion(): Response
    {
        return $this->render('main/motion.html.twig');
    }

    #[Route('/motion/load', name: 'motion_load')]
    public function m_load(): JsonResponse
    {
        $refresh = $this->getUser()->getMouvementPir()->getGraphRafraich();
        $cam = $this->getUser()->getMouvementPir()->getEnreg();
        $alert = $this->getUser()->getMouvementPir()->getAlert();

        return $this->json(['refresh_graph' => $refresh, 'allow_cam' => $cam, 'allow_alert' => $alert]);
    }

    #[Route('/motion/save', name: 'motion_save', methods: 'POST')]
    public function m_save(Request $request): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $member = $this->getUser();
            $datas = $member->getMouvementPir();
            $datas->setGraphRafraich((int) $request->get('refresh'));
            $datas->setEnreg($request->get('cam'));
            $datas->setAlert($request->get('alert'));

            $member->setMouvementPir($datas);
            $this->em->flush();

            return $this->json(['status' => 'success', 'message' => 'success message']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'not valid json']);
        }
    }

    #[Route('/leds', name: 'leds')]
    public function leds(): Response
    {
        // if (file_exists($this->getParameter('data_directory') . '/pir_sensor.data')) {
        //     $motion_stat = file_get_contents($this->getParameter('data_directory') . '/pir_sensor.data');

        //     return $this->render('main/motion.html.twig', [
        //         'motion_stat' => $motion_stat
        //     ]);
        // }
        return $this->render('main/leds.html.twig');
    }

    #[Route('/leds/load', name: 'leds_load')]
    public function l_load(): JsonResponse
    {
        $rgb = $this->getUser()->getLedsStrip()->getRgb();
        $etat = $this->getUser()->getLedsStrip()->getEtat();
        $debut_time = $this->getUser()->getLedsStrip()->getHOn();
        $fin_time = $this->getUser()->getLedsStrip()->getHOff();
        $email = $this->getUser()->getLedsStrip()->getEmail();
        $effet = $this->getUser()->getLedsStrip()->getEffet();

        return $this->json(['0' => $rgb, '1' => $etat, '2' => $debut_time, '3' => $fin_time, '4' => $email, '5' => $effet]);
    }

    #[Route('/leds/save', name: 'leds_save', methods: 'POST')]
    public function l_save(Request $request): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $member = $this->getUser();
            $datas = $member->getLedsStrip();
            $datas->setRgb($request->get('rgb'));
            $datas->setEtat($request->get('etat'));
            $datas->setEmail($request->get('email'));
            $datas->setEffet($request->get('effet'));

            $member->setLedsStrip($datas);
            $this->em->flush();

            return $this->json(['status' => 'success', 'message' => 'success message']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'not valid json']);
        }
    }

    #[Route('/leds/timer', name: 'leds_timer', methods: 'POST')]
    public function l_timer(Request $request): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $member = $this->getUser();
            $datas = $member->getLedsStrip();
            // if ($request->getHOn('h_on')) =  'null' or ($request->getHOn('h_off')) =  'null' {

            // }
            $datas->setHOn($request->get('h_on'));
            $datas->setHOff($request->get('h_off'));

            $member->setLedsStrip($datas);
            $this->em->flush();

            return $this->json(['status' => 'success', 'message' => 'success message']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'not valid json']);
        }
    }

    #[Route('/temp', name: 'temp')]
    public function temp(): Response
    {

        return $this->render('main/temp.html.twig');
    }

    #[Route('/temp/load', name: 'temp_load')]
    public function t_load()
    {
        $fileContent_int = file_get_contents($this->getParameter('data_directory') . '/temp_sensor.data');
        $fileContent_int = '{"data_int":' . $fileContent_int . ',';
        $fileContent_ext = file_get_contents($this->getParameter('data_directory') . '/temp_ext_sensor.data');
        $fileContent_ext = ' "data_ext":' . $fileContent_ext . ',';
        $fileContent_bas = file_get_contents($this->getParameter('data_directory') . '/temp_bas_sensor.data');
        $fileContent_bas = ' "data_bas":' . $fileContent_bas . '}';
        $fileContent = $fileContent_int . $fileContent_ext . $fileContent_bas;
        $fileContent = json_encode($fileContent);
        return new Response($fileContent);
    }

    #[Route('/gallery', name: 'gallery')]
    public function gallery(ManagerRegistry $doctrine): Response
    {
        $nbColonne = 4;
        $colonneSaut = 0;
        $chaine_html = '<div class="row d-flex justify-content-center">';
        $security_rep = $doctrine->getRepository(Security::class);

        foreach ($security_rep->findAllMedia(8) as $key => $val) {
            $chemin_video = substr($val['filename'], 27);
            $nom_video = substr($val['filename'], 34);
            $chemin_image = preg_replace("/.mp4$/", '.jpg', $chemin_video);
            $nom_image = preg_replace("/_event\d+.mp4$/", '', $nom_video);
            if ($colonneSaut != 0 && $colonneSaut % $nbColonne == 0) {
                $chaine_html .= '</div><div class="row d-flex justify-content-center">';
            }
            $chaine_html .= '<div class="col-12 col-sm-8 col-md-5 col-md-4 col-xl-3 text-center"><a href="/gallery/player?event=' . $nom_video . '&id=' . $val['id'] . '"><img src=' . $chemin_image . ' alt="Vidéo en cours de création..."></a><div class="text-center"><span class="petite nom">' . $nom_image . '</span><input type="checkbox" class="ms-2 video_select" data-url="' . '../' . $chemin_video . '" data-id_nb="' . $val['id'] . '" data-name="' . $nom_video . '"></div></div>';
            $colonneSaut++;
        }
        $chaine_html .= '</div>';

        return $this->render('main/gallery.html.twig', ['videos' => $chaine_html]);
    }

    #[Route('/gallery/player', name: 'player')]
    public function play(): Response
    {
        $video = $this->request->query->get('event');
        $id = $this->request->query->get('id');

        return $this->render('main/player.html.twig', ['video' => $video, 'id' => $id]);
    }

    #[Route('/gallery/erase', name: 'erase')]
    public function erase(ManagerRegistry $doctrine): Response
    {
        $single_value = preg_split("/,/", $this->request->query->get('value'));
        foreach ($single_value as $i) {
            if (isset($i)) {
                $values = preg_split("/#/", $i);
                $image = preg_replace('/mp4/i', 'jpg', $values[1]);
                if ((isset($values[0])) && (isset($values[1]) && (preg_match('/^\d+$/', $values[0])) && (preg_match('/\d{2}-\d{2}-\d{4}_\d{2}h\d{2}m\d{2}s_event\d+\.mp4$/', $values[1])))) {

                    $entityManger = $this->getDoctrine()->getManager();
                    $security_rep = $entityManger->getRepository(Security::class)->find($values[0]);
                    $entityManger->remove($security_rep);
                    $entityManger->flush($security_rep);

                    unlink('images/' . $values[1]);
                    unlink('images/' . $image);
                } else {
                    $retour = "error";
                }
            } else {
                $retour = "error";
            }
        }
        return $this->redirectToRoute('gallery');
    }

    #[Route('/gallery/discusage', name: 'discusage')]
    public function du_load(): JsonResponse
    {
        $espace_total = $this->getUser()->getMouvementPir()->getEspaceTotal();
        $espace_dispo = $this->getUser()->getMouvementPir()->getEspaceDispo();
        $taux_utilisation = $this->getUser()->getMouvementPir()->getTauxUtilisation();

        return $this->json(['0' => $espace_total, '1' => $espace_dispo, '2' => $taux_utilisation]);
    }
}
