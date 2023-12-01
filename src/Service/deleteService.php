<?php

namespace App\Service;

use App\Repository\SecurityRepository;

class deleteService{

    public function __construct(private SecurityRepository $securityRepository)
    {
    }
    public function deleteFile(array $idList): bool
    {

        foreach ($idList as $idToDelete) {
            $video = $this->securityRepository->find($idToDelete);
            $videoFile = basename($video->getFilename());
            $imageFile = preg_replace('/mp4/i', 'jpg', $videoFile);
            if ( file_exists('images/' . $videoFile) && file_exists('images/' . $imageFile)) {
                if (unlink('images/' . $videoFile) && unlink('images/' . $imageFile)) {
                    $this->securityRepository->remove($video, true);
                }
            }
            else {
                return false;
            }
        }
        return true;
    }
}
