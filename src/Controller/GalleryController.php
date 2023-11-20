<?php

namespace App\Controller;

use App\Entity\Security;
use App\Repository\SecurityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

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

    #[Route('/gallery/delete', name: 'delete', methods: 'POST')]
    public function delete(Request $request, SecurityRepository $securityRepository): JsonResponse
    {
        $contentType = $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'application/json')) {
            $data = json_decode($request->getContent(), true);
            if(isset($data)){
                foreach ($data as $idToDelete) {
                    $video = $securityRepository->find($idToDelete);
                    $videoFile = basename($video->getFilename());
                    $imageFile = preg_replace('/mp4/i', 'jpg', $videoFile);
                    if ( file_exists('images/' . $videoFile) && file_exists('images/' . $imageFile)) {
                        if (unlink('images/' . $videoFile) && unlink('images/' . $imageFile)) {
                            $securityRepository->remove($video, true);
                        }
                    }
                }
                return new JsonResponse(['redirect' => $this->generateUrl('gallery')], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->json(['status' => 'error', 'message' => 'not valid video']);
            }
        }
        else {
           return $this->json(['status' => 'error', 'message' => 'not valid json']);
       }
    }

    #[Route('/gallery/download', name: 'download', methods: 'POST')]
    public function download(Request $request, SecurityRepository $securityRepository): JsonResponse
    {
        $contentType = $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'application/json')) {
            $data = json_decode($request->getContent(), true);

            if(isset($data)) {
                $zip = new ZipArchive();
                $tempZipFilePath = $this->getParameter('temp_directory') . '/video.zip';
                if ($zip->open($tempZipFilePath, ZipArchive::CREATE) === TRUE) {
                    foreach ($data as $idToZipFile) {
                        $video = $securityRepository->find($idToZipFile);
                        $videoFile = basename($video->getFilename());
                        $zip->addFile('images/' . $videoFile, $videoFile);
                    }
                }
                $zip->close();
                return $this->json(['zip_file' => 'temp/' . basename($tempZipFilePath)]);
            }
            else {
                return $this->json(['status' => 'error', 'message' => 'not valid video']);
            }
        }
        else {
            return $this->json(['status' => 'error', 'message' => 'not valid json']);
        }
    }

    #[Route('/gallery/clean', name: 'clean', methods: 'GET')]
    public function deleteZip(): JsonResponse
    {
        $path = $this->getParameter('temp_directory');
        if ($tempDir = opendir( $path )) {
            while (false !== ($file = readdir($tempDir))) {
                if ($file != "." && $file != "..") {
                    unlink($path . '/' . $file);
                }
            }
            closedir($tempDir);
        }
        return new JsonResponse();
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
