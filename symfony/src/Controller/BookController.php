<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;

final class BookController extends AbstractController
{
    #[Route('/book/{id}', name: 'app_book_show')]
    public function show(BookRepository $bookRepository, int $id): Response
    {
        $bookData = $bookRepository->find($id);

        $book = [
            'id' => $bookData->getId(),
            'title' => $bookData->getTitle(),
            'author' => $bookData->getAuthor(),
            'description' => $bookData->getDescription(),
            'image' => $bookData->getImage() ? 'uploads/' . $bookData->getImage() : 'image/default.jpg',
        ];

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
