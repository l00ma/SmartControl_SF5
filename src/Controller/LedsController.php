<?php

namespace App\Controller;

use App\Repository\MembersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LedsController extends AbstractController
{
    #[Route('/leds', name: 'leds')]
    public function leds(): Response
    {
        // if (file_exists($this->getParameter('data_directory') . '/pir_sensor.data')) {
        //     $motion_stat = file_get_contents($this->getParameter('data_directory') . '/pir_sensor.data');

        //     return $this->render('main/index.html.twig', [
        //         'motion_stat' => $motion_stat
        //     ]);
        // }
        return $this->render('leds/index.html.twig');
    }

    #[Route('/leds/load', name: 'leds_load')]
    public function l_load(): JsonResponse
    {
        $rgb = $this->getUser()->getLedsStrip()->getRgb();
        $etat = $this->getUser()->getLedsStrip()->getEtat();
        $debut_time = $this->getUser()->getLedsStrip()->getHOn();
        $fin_time = $this->getUser()->getLedsStrip()->getHOff();
        $email = $this->getUser()->getLedsStrip()->getEmail();
        $effet = $this->getUser()->getLedsStrip()->getEffet();

        return $this->json(['0' => $rgb, '1' => $etat, '2' => $debut_time, '3' => $fin_time, '4' => $email, '5' => $effet]);
    }

    #[Route('/leds/save', name: 'leds_save', methods: 'POST')]
    public function l_save(Request $request, MembersRepository $membersRepository): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            $datas = $user->getLedsStrip();
            $datas->setRgb($request->get('rgb'));
            $datas->setEtat($request->get('etat'));
            $datas->setEmail($request->get('email'));
            $datas->setEffet($request->get('effet'));

            $user->setLedsStrip($datas);
            $membersRepository->add($user, true);

            return $this->json(['status' => 'success', 'message' => 'success message']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'not valid json']);
        }
    }

    #[Route('/leds/timer', name: 'leds_timer', methods: 'POST')]
    public function l_timer(Request $request, MembersRepository $membersRepository): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            $datas = $user->getLedsStrip();
            // if ($request->getHOn('h_on')) =  'null' or ($request->getHOn('h_off')) =  'null' {

            // }
            $datas->setHOn($request->get('h_on'));
            $datas->setHOff($request->get('h_off'));

            $user->setLedsStrip($datas);
            $membersRepository->add($user, true);

            return $this->json(['status' => 'success', 'message' => 'success message']);
        } else {
            return $this->json(['status' => 'error', 'message' => 'not valid json']);
        }
    }
}
