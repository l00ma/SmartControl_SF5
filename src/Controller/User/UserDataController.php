<?php

namespace App\Controller\User;

use App\Repository\MembersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserDataController extends AbstractController
{

    #[Route('/welcome/user', name: 'user.edit', methods: 'GET|POST')]
    public function edit(Request $request, UserPasswordHasherInterface $passwordEncoder, MembersRepository $membersRepository)
    {
        $user = $this->getUser();
        if ($request->isMethod('POST')) {
            if ($request->request->get('pass1') == $request->request->get('pass2')) {
                $user->setUsername($request->request->get('user'));
                $user->setEmail($request->request->get('email'));
                $user->setPassword($passwordEncoder->hashPassword($user, $request->request->get('pass1')));
                $membersRepository->add($user, true);
                $this->addFlash('success', 'Credentials successfully saved');
            } else {
                $this->addFlash('error', 'Passwords are different');
            }
        }

        return $this->render('user/edit.html.twig');
    }
}
