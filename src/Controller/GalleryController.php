<?php

namespace App\Controller;

use App\Entity\Security;
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

    #[Route('/gallery/player/{id<^[0-9]+$>}', name: 'player', methods: ['GET'])]
    public function play(Security $security): Response
    {
        $security->setRelativeFilename(basename($security->getFilename()));
        return $this->render('gallery/player.html.twig', ['video' => $security]);
    }

    #[Route(['/delete', '/gallery/player/delete'], name: 'delete', methods: 'POST')]
    public function delete(Request $request, SecurityRepository $securityRepository): JsonResponse
    {
        $contentType = $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'application/json')) {
            $data = json_decode($request->getContent(), true);
            if (true) {
                return $this->json(['status' => 'success', 'message' => $data]);
            } else {
                return $this->json(['status' => 'error', 'message' => 'not valid json']);
            }
        }
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

    #[Route(['/download', '/gallery/player/download'], name: 'download', methods: 'POST')]
    public function download(Request $request, SecurityRepository $securityRepository): JsonResponse
    {
        $contentType = $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'application/json')) {
            $data = json_decode($request->getContent(), true);
            if (true) {
                return $this->json(['status' => 'success', 'message' => $data]);
            } else {
                return $this->json(['status' => 'error', 'message' => 'not valid json']);
            }
        }
    }

    #[Route('/gallery/discusage', name: 'discusage')]
    public function du_load(): JsonResponse
    {
        $totalSpace = $this->getUser()->getMouvementPir()->getEspaceTotal();
        $freeSpace = $this->getUser()->getMouvementPir()->getEspaceDispo();
        $usedRate = $this->getUser()->getMouvementPir()->getTauxUtilisation();

        return $this->json(['0' => $totalSpace, '1' => $freeSpace, '2' => $usedRate]);
    }
}
