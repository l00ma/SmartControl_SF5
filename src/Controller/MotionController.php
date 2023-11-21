<?php

namespace App\Controller;

use App\Repository\MembersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MotionController extends AbstractController
{
    #[Route('/motion', name: 'motion')]
    public function motion(): Response
    {
        return $this->render('motion/index.html.twig');
    }

    #[Route('/motion/load', name: 'motion_load')]
    public function m_load(): Response
    {
        $data_pir = file_get_contents($this->getParameter('data_directory') . '/pir_sensor.data');
        $refresh = $this->getUser()->getMouvementPir()->getGraphRafraich();
        $cam = $this->getUser()->getMouvementPir()->getEnreg();
        $alert = $this->getUser()->getMouvementPir()->getAlert();

        // on crée un tableau associatif avec les données
        $data = [
            "refresh_graph" => json_decode($refresh, true),
            "allow_cam" => json_decode($cam, true),
            "allow_alert" => json_decode($alert, true),
            "data_pir" => json_decode($data_pir, true)
        ];

        // on convertit le tout en JSON
        $json_data = json_encode($data);
        return new Response($json_data);
    }

    #[Route('/motion/save', name: 'motion_save', methods: 'POST')]
    public function m_save(Request $request, MembersRepository $membersRepository): JsonResponse
    {
        $contentType = $request->headers->get('Content-Type');
        if (str_starts_with($contentType, 'application/json')) {
            $incomingMouvData = json_decode($request->getContent(), true);
            $user = $this->getUser();
            $dataToSave = $user->getMouvementPir();
            $camAndAlertTest = [0,1];
            $refreshTest = [5,10,15];
            if(in_array($incomingMouvData['refresh'],$refreshTest)) $dataToSave->setGraphRafraich($incomingMouvData['refresh']);
            if(in_array($incomingMouvData['cam'],$camAndAlertTest)) $dataToSave->setEnreg($incomingMouvData['cam']);
            if(in_array($incomingMouvData['alert'],$camAndAlertTest)) $dataToSave->setAlert($incomingMouvData['alert']);

            $user->setMouvementPir($dataToSave);
            $membersRepository->add($user, true);

            return $this->json(['status' => 'success', 'message' => 'success message']);
        }
        return $this->json(['status' => 'error', 'message' => 'not valid json']);
    }
}
