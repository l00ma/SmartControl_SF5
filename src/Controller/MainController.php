<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MembersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @var MembersRepository;
     */
    private $repository;

    public function __construct(MembersRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/welcome', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('main/welcome.html.twig');
    }

    #[Route('/motion', name: 'motion')]
    public function motion(): Response
    {
        // if (file_exists($this->getParameter('data_directory') . '/pir_sensor.data')) {
        //     $motion_stat = file_get_contents($this->getParameter('data_directory') . '/pir_sensor.data');

        //     return $this->render('main/motion.html.twig', [
        //         'motion_stat' => $motion_stat
        //     ]);
        // }
        return $this->render('main/motion.html.twig');
    }

    #[Route('/motion/load', name: 'motion_load')]
    public function load(): JsonResponse
    {
        $refresh = $this->getUser()->getMouvementPir()->getGraphRafraich();
        $cam = $this->getUser()->getMouvementPir()->getEnreg();
        $alert = $this->getUser()->getMouvementPir()->getAlert();
        //var_dump($refresh, $cam, $alert);

        return $this->json(['refresh_graph' => $refresh, 'allow_cam' => $cam, 'allow_alert' => $alert]);
    }

    #[Route('/motion/save', name: 'motion_save', methods: 'GET|POST')]
    public function save(Request $request)
    {
        $member = $this->getUser();

        $datas = $member->getMouvementPir();
        $datas->setGraphRafraich((int) $request->get('refresh'));
        $datas->setEnreg($request->get('cam'));
        $datas->setAlert($request->get('alert'));

        $member->setMouvementPir($datas);
        $this->em->flush();

        return $this->render('main/motion.html.twig');
    }
}
