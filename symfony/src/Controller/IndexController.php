<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(BookRepository $bookRepository, Request $request): Response
    {
        if($page = $request->query->get('page')) {
            $page = (int) $page;
        } else {
            $page = 1;
        }
        $itemsPerPage = 10;

        $booksData = $bookRepository->findPaginatedBooks($page, $itemsPerPage);

        foreach ($booksData as $book) {
            $books[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'image' => $book->getImage() ? 'uploads/' . $book->getImage() : 'image/default.jpg',
            ];
        }

        $totalBooks = $bookRepository->countBooks();
        $totalPages = ceil($totalBooks / $itemsPerPage);

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'total_pages' => $totalPages,
            'current_page' => $page,
        ]);
    }
}
