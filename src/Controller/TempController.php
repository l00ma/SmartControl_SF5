<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TempController extends AbstractController
{
    #[Route('/thermo', name: 'temp')]
    public function temp(): Response
    {
        return $this->render('temp/index.html.twig');
    }

    #[Route('/temp/load', name: 'temp_load')]
    public function t_load(): Response
    {
        $data_int = file_get_contents($this->getParameter('data_directory') . '/temp_sensor.data');
        $data_ext = file_get_contents($this->getParameter('data_directory') . '/temp_ext_sensor.data');
        $data_bas = file_get_contents($this->getParameter('data_directory') . '/temp_bas_sensor.data');

        // on crée un tableau associatif avec les données
        $data = [
            "data_int" => json_decode($data_int, true),
            "data_ext" => json_decode($data_ext, true),
            "data_bas" => json_decode($data_bas, true)
        ];

        // on convertit le tout en JSON
        $json_data = json_encode($data);
        return new Response($json_data);
    }
}
