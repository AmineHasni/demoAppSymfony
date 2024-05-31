<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    private $bookRepository;
    private $entityManager;

    public function __construct(BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
    }

    public function createBook(array $data): Book
    {
        $book = new Book();
        $book->setName($data['name']);
        $book->setAuthor($data['author'] ?? null);

        if (isset($data['shelf'])) {
            $book->setShelf($data['shelf']);
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    public function updateBook(int $id, array $data): ?Book
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            return null;
        }

        $book->setName($data['name'] ?? $book->getName());
        $book->setAuthor($data['author'] ?? $book->getAuthor());

        if (isset($data['shelf'])) {
            $book->setShelf($data['shelf']);
        }

        $this->entityManager->flush();

        return $book;
    }

    public function deleteBook(int $id): bool
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            return false;
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return true;
    }

    public function getBook(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getBooksByShelfId(int $shelfId): array
    {
        return $this->bookRepository->findBy(['shelf' => $shelfId]);
    }
}
