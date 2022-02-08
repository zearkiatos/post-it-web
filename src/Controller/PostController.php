<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Exception;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\PostType;
use App\Entity\Post;

class PostController extends AbstractController
{
    #[Route('/post/new', name: 'new_post')]
    public function newPost(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $formPost = $this->createForm(PostType::class, $post);
        $formPost->handleRequest($request);
        if ($formPost->isSubmitted() && $formPost->isValid()) {
            $image = $formPost->get('image_name')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                $post->setImageName($newFilename);
                try {
                    $image->move($this->getParameter(('post_directory')), $newFilename);
                    $user = $this->getUser();
                    $post->setUser($user);
                    $entityManager->persist($post);
                    $entityManager->flush();
                } catch (FileException $exception) {
                    throw new Exception(("Ups something was wrong with your file"));
                }
            }

            return $this->redirectToRoute('new_post');
        }
        return $this->render('post/post_new.html.twig', [
            'formPost' => $formPost->createView(),
        ]);
    }
}
