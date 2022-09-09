<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/welcome', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('main/welcome.html.twig');
    }

    #[Route('/welcome/motion', name: 'motion')]
    public function motion(): Response
    {
        if (file_exists($this->getParameter('data_directory') . '/pir_sensor.data')) {
            $motion_stat = file_get_contents($this->getParameter('data_directory') . '/pir_sensor.data');

            return $this->render('main/motion.html.twig', [
                'motion_stat' => $motion_stat
            ]);
        }
    }
}
