<?php

namespace App\Controller\User;

use App\Repository\MembersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserDataController extends AbstractController
{

    /**
     * @var MembersRepository
     */
    private $repository;

    public function __construct(MembersRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/welcome/user", name="user.edit", methods="GET|POST")
     */
    public function edit(Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $user = $this->getUser();
        if ($request->isMethod('POST')) {
            if (!empty($request->request->get('user')) && !empty($request->request->get('email'))) {
                if (!empty($request->request->get('pass1')) or !empty($request->request->get('pass2'))) {
                    if ($request->request->get('pass1') == $request->request->get('pass2')) {
                        $user->setUsername($request->request->get('user'));
                        $user->setEmail($request->request->get('email'));
                        $user->setPassword($passwordEncoder->hashPassword($user, $request->request->get('pass1')));
                        $this->em->flush();
                        $this->addFlash('success', 'Credentials successfully saved');
                    } else {
                        $this->addFlash('error', 'Passwords are different');
                    }
                } else {
                    $this->addFlash('error', 'Password not set');
                }
            } else {
                $this->addFlash('error', 'You must set a user name and an email');
            }
        }

        return $this->render('user/edit.html.twig');
    }
}
