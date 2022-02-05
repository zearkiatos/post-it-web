<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;

class StandardController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function index(EntityManager $entityManager, Request $request): Response
    {
        $user = new User();
        $registerForm = $this->createForm(UserType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('standard/index.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }
}
