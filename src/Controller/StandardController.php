<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Post;
use App\Shared\Constants\Roles;
use Knp\Component\Pager\PaginatorInterface;

class StandardController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request) {
        $query = $entityManager->getRepository(Post::class)->getPostsQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('standard/index.html.twig', [
            'paginations' => $pagination
        ]);
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
