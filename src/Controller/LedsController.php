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
        $contentType = $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'application/json')) {
            $incomingLedsData = json_decode($request->getContent(), true);
            $user = $this->getUser();
            $dataToSave = $user->getLedsStrip();
            $dataToSave->setRgb($incomingLedsData['rgb']);
            $dataToSave->setEtat($incomingLedsData['etat']);
            $dataToSave->setEmail($incomingLedsData['email']);
            $dataToSave->setEffet($incomingLedsData['effet']);

            $user->setLedsStrip($dataToSave);
            $membersRepository->add($user, true);

            return $this->json(['status' => 'success', 'message' => 'success message']);
        }
        return $this->json(['status' => 'error', 'message' => 'not valid json']);
    }

    #[Route('/leds/timer', name: 'leds_timer', methods: 'POST')]
    public function l_timer(Request $request, MembersRepository $membersRepository): JsonResponse
    {
        $contentType = $request->headers->get('Content-Type');
        // on vérifie que la requete est bien du JSON
        if (str_starts_with($contentType, 'application/json')) {
            $incomingLedsData = json_decode($request->getContent(), true);
            $user = $this->getUser();
            $datas = $user->getLedsStrip();
            $datas->setHOn($incomingLedsData['h_on']);
            $datas->setHOff($incomingLedsData['h_off']);
            $datas->setTimer($incomingLedsData['timer']);
            $datas->setEmail($incomingLedsData['email']);

            $user->setLedsStrip($datas);
            $membersRepository->add($user, true);

            return $this->json(['status' => 'success', 'message' => 'success message']);
        }
        return $this->json(['status' => 'error', 'message' => 'not valid json']);
    }
}
