<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
        ]);
    }

    #[Route('/book/{id}', name: 'app_book_show')]
    public function show(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController',
            'book' => $book,
        ]);
    }
}
