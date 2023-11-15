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
        $video = [];
        foreach ($securityRepository->findAllMedia(8) as $val) {
            $videoId = $val['id'];
            $videoFilename = basename($val['filename']);
            $videoPath = basename(dirname($val['filename'])) . '/' . $videoFilename;
            $imageName = preg_replace("/_event\d+.mp4$/", '', $videoFilename);
            $imagePath = preg_replace("/.mp4$/", '.jpg', $videoPath);
            $video[] = [
                'videoId' => $videoId,
                'videoFilename' => $videoFilename,
                'videoPath' => $videoPath,
                'imageName' => $imageName,
                'imagePath' => $imagePath
            ];
        }
        return $this->render('gallery/index.html.twig', ['video' => $video]);
    }

    #[Route('/gallery/player', name: 'player')]
    public function play(Request $request): Response
    {
        $video = $request->query->get('event');
        $id = $request->query->get('id');

        return $this->render('gallery/player.html.twig', ['video' => $video, 'id' => $id]);
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
