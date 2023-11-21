<?php

namespace App\Service;

use App\Repository\SecurityRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use ZipArchive;

class downloadService{

    public function __construct(private SecurityRepository $securityRepository, private ParameterBagInterface $params)
    {
    }
    public function zipFile(array $idList): string
    {
        $zip = new ZipArchive();
        $tempZipFilePath = $this->params->get('temp_directory') . '/events.zip';
        if ($zip->open($tempZipFilePath, ZipArchive::CREATE)) {
            foreach ($idList as $idToZipFile) {
                $video = $this->securityRepository->find($idToZipFile);
                $videoFile = basename($video->getFilename());
                $zip->addFile('images/' . $videoFile, $videoFile);
            }
        }
        $zip->close();
        return $tempZipFilePath;
    }
}
