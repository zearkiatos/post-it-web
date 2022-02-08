<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\UserType;
use App\Shared\Constants\Roles;

class StandardController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index() {
        return $this->render('standard/index.html.twig');
    }
    #[Route('/register', name: 'register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $registerForm = $this->createForm(UserType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setRoles([Roles::ROLE_USER]);
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('standard/register.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }
}
