<?php

namespace App\Controller;

use App\Entity\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MembersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $films = $security_rep->findByMedia(8);
        $emails = $security_rep->findByMedia(2);

        return $this->json(['etat' => $etat, 'temp_int' => $temp_int, 'temp_ext' => $temp_ext, 'temp_bas' => $temp_bas, 'enreg' => $enreg, 'enreg_detect' => $enreg_detect, 'alert' => $alert, 'pression' => $pression, 'vitesse_vent' => $vitesse_vent, 'direction_vent' => $direction_vent, 'location' => $location, 'humidite' => $humidite, 'weather' => $weather, 'icon_id' => $icon_id, 'leve_soleil' => $leve_soleil, 'couche_soleil' => $couche_soleil, 'temp_f1' => $temp_f1, 'temp_f2' => $temp_f2, 'temp_f3' => $temp_f3, 'time_f1' => $time_f1, 'time_f2' => $time_f2, 'time_f3' => $time_f3, 'weather_f1' => $weather_f1, 'weather_f2' => $weather_f2, 'weather_f3' => $weather_f3, 'icon_f1' => $icon_f1, 'icon_f2' => $icon_f2, 'icon_f3' => $icon_f3, 'film' => $films, 'email' => $emails]);
    }

    #[Route('/motion', name: 'motion')]
    public function motion(): Response
    {
        // if (file_exists($this->getParameter('data_directory') . '/pir_sensor.data')) {
        //     $motion_stat = file_get_contents($this->getParameter('data_directory') . '/pir_sensor.data');

        //     return $this->render('main/motion.html.twig', [
        //         'motion_stat' => $motion_stat
        //     ]);
        // }
        return $this->render('main/motion.html.twig');
    }

    #[Route('/motion/load', name: 'motion_load')]
    public function m_load(): JsonResponse
    {
        $refresh = $this->getUser()->getMouvementPir()->getGraphRafraich();
        $cam = $this->getUser()->getMouvementPir()->getEnreg();
        $alert = $this->getUser()->getMouvementPir()->getAlert();
        //var_dump($refresh, $cam, $alert);

        return $this->json(['refresh_graph' => $refresh, 'allow_cam' => $cam, 'allow_alert' => $alert]);
    }

    #[Route('/motion/save', name: 'motion_save', methods: 'GET|POST')]
    public function m_save(Request $request)
    {
        $member = $this->getUser();

        $datas = $member->getMouvementPir();
        $datas->setGraphRafraich((int) $request->get('refresh'));
        $datas->setEnreg($request->get('cam'));
        $datas->setAlert($request->get('alert'));

        $member->setMouvementPir($datas);
        $this->em->flush();

        return $this->render('main/motion.html.twig');
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
}
