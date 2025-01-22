<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;
use App\Form\AddBookType;
use Symfony\Component\HttpFoundation\Request;

final class BookController extends AbstractController
{
    #[Route('/book/add/{id}', name: 'app_book_add_by_id')]
    public function addBookById(EntityManagerInterface $entityManager, Request $request, BookRepository $bookRepository, int $id): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $book = $bookRepository->find($id);

        $user = $this->getUser();

        $user->addBookCollection($book);

        $entityManager->persist($user);
        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }

    #[Route('/book/add', name: 'app_book_add')]
    public function addBook(EntityManagerInterface $entityManager, Request $request, BookRepository $bookRepository): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(AddBookType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            $user = $this->getUser();

            $isbn = $book->getISBN();
            $existingBook = $bookRepository->findOneBy(['ISBN' => $isbn]);

            if($existingBook) {
                $user->addBookCollection($existingBook);

                $entityManager->persist($user);
                $entityManager->persist($existingBook);

                $entityManager->flush();

                return $this->redirectToRoute('app_book_list');
            }

            $user->addBookCollection($book);

            $entityManager->persist($user);
            $entityManager->persist($book);

            $entityManager->flush();

            return $this->redirectToRoute('app_book_list');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/book/remove/{id}', name: 'app_book_remove')]
    public function removeBook(EntityManagerInterface $entityManager, BookRepository $bookRepository, int $id): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $book = $bookRepository->find($id);

        $user = $this->getUser();

        $user->removeBookCollection($book);

        $entityManager->persist($user);
        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }

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

    #[Route('/book', name: 'app_book_list')]
    public function bookList(): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $booksData = $user->getBookCollection();

        if(empty($booksData)) {
            return $this->render('book/list.html.twig', [
                'books' => [],
            ]);
        } 

        $books = [];
        foreach ($booksData as $book) {
            $books[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'image' => $book->getImage() ? 'uploads/' . $book->getImage() : 'image/default.jpg',
            ];
        }

        return $this->render('book/list.html.twig', [
             'books' => $books,
        ]);
    }
}
