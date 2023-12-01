<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LiveController extends AbstractController
{
    #[Route('/live', name: 'live')]
    public function index(Request $request): Response
    {
        $ip = $request->getHttpHost();
        return $this->render('live/index.html.twig', [
            //'hostIp' => $ip,
            'hostIp' => 'https://picsum.photos/400/300',
        ]);
    }
}
