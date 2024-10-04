<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;


class AuthorController extends AbstractController
{
    #[Route("/author/{id}", name:"show_author")]
public function showAuthor(int $id): Response
{
    $authors = [
        ['id' => 1, 'picture' => '/images/Victor.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => '/images/wiliam.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => '/images/Taha.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];
    $author = null;
    foreach ($authors as $a) {
        if ($a['id'] === $id) {
            $author = $a;
            break;
        }
    }
    if ($author === null) {
        throw $this->createNotFoundException('Author not found');
    }
    return $this->render('show.html.twig', [
        'author' => $author,
    ]);
}

    #[Route("/authors", name:"list_authors")]
    public function listAuthors(): Response
    {
        $authors = [
            ['id' => 1, 'picture' => '/images/Victor.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['id' => 2, 'picture' => '/images/wiliam.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['id' => 3, 'picture' => '/images/Taha.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        if (!isset($authors) || empty($authors)) {
            $this->addFlash('notice', 'Aucun auteur disponible.');
        }
        

        $totalBooks = array_reduce($authors, function ($carry, $author) {
            return $carry + $author['nb_books'];
        }, 0);

        return $this->render('showlist.html.twig', [
            'authors' => $authors,
            'totalBooks' => $totalBooks,
        ]);
    }

    #[Route("/authorss", name:"list_authors")]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('showauthor.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route("/addStaticAuthor", name:"addStaticAuthor")]

    public function addStaticAuthor(EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $author->setUsername('Raed');
        $author->setEmail('Raed@gmail.com');

        $entityManager->persist($author);
        $entityManager->flush();

        return new Response('Auteur ajouté avec succès : ' . $author->getUsername());
    }

    #[Route("/addAuthor", name:"add_author")]
    public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('show_author');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/show-author", name:"show_author")]
    public function showAuthors(EntityManagerInterface $entityManager): Response 
    {
        $authors = $entityManager->getRepository(Author::class)->findAll();

        return $this->render('showauthor.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route("/edit/{id}", name:"edit_author")]
    public function editAuthor(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuthorType::class, $author); 

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('show_author');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name:"delete_author")]
    public function deleteAuthor(Author $author, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($author);
        $entityManager->flush(); 

        return $this->redirectToRoute('show_author');
    }


}
