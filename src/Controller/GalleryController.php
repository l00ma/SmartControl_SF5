<?php

namespace App\Controller;

use App\Repository\SecurityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'gallery')]
    public function gallery(SecurityRepository $securityRepository): Response
    {
        $nbColonne = 4;
        $colonneSaut = 0;
        $chaine_html = '<div class="row d-flex justify-content-center">';

        foreach ($securityRepository->findAllMedia(8) as $val) {
            //$chemin_video = substr($val['filename'], 27);
            $nom_video = basename($val['filename']);
            $chemin_video = basename(dirname($val['filename'])) . '/' . $nom_video;
            $chemin_image = preg_replace("/.mp4$/", '.jpg', $chemin_video);
            $nom_image = preg_replace("/_event\d+.mp4$/", '', $nom_video);
            if ($colonneSaut != 0 && $colonneSaut % $nbColonne == 0) {
                $chaine_html .= '</div><div class="row d-flex justify-content-center">';
            }
            $chaine_html .= '<div class="col-12 col-sm-8 col-md-5 col-md-4 col-xl-3 text-center"><a href="/gallery/player?event=' . $nom_video . '&id=' . $val['id'] . '"><img src=' . $chemin_image . ' alt="Vidéo en cours de création..."></a><div class="text-center"><span class="petite nom">' . $nom_image . '</span><input type="checkbox" class="ms-2 video_select" data-url="' . '../' . $chemin_video . '" data-id_nb="' . $val['id'] . '" data-name="' . $nom_video . '"></div></div>';
            $colonneSaut++;
        }
        $chaine_html .= '</div>';

        return $this->render('gallery/index.html.twig', ['videos' => $chaine_html]);
    }

    #[Route('/gallery/player', name: 'player')]
    public function play(Request $request): Response
    {
        $video = $request->query->get('event');
        $id = $request->query->get('id');

        return $this->render('main/player.html.twig', ['video' => $video, 'id' => $id]);
    }

    #[Route('/gallery/erase', name: 'erase')]
    public function erase(Request $request, SecurityRepository $securityRepository): Response
    {

        $single_value = preg_split("/,/", $request->query->get('value'));
        foreach ($single_value as $i) {
            if (isset($i)) {
                $values = preg_split("/#/", $i);
                $image = preg_replace('/mp4/i', 'jpg', $values[1]);
                if ((isset($values[0])) && (isset($values[1]) && (preg_match('/^\d+$/', $values[0])) && (preg_match('/\d{2}-\d{2}-\d{4}_\d{2}h\d{2}m\d{2}s_event\d+\.mp4$/', $values[1])))) {

                    $security_rep = $securityRepository->find($values[0]);
                    $securityRepository->remove($security_rep, true);
                    //$securityRepository->flush($security_rep);

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
