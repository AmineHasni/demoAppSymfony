<?php
namespace App\Service;

use App\Entity\Shelf;
use App\Entity\Book;
use App\Repository\ShelfRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShelfService
{
    private $shelfRepository;
    private $entityManager;

    public function __construct(ShelfRepository $shelfRepository, EntityManagerInterface $entityManager)
    {
        $this->shelfRepository = $shelfRepository;
        $this->entityManager = $entityManager;
    }

    public function createShelf(array $data): Shelf
    {
        $shelf = new Shelf();
        $shelf->setPosition($data['position']);

        if (isset($data['library'])) {
            $shelf->setLibrary($data['library']);
        }

        $this->entityManager->persist($shelf);
        $this->entityManager->flush();

        return $shelf;
    }

    public function updateShelf(int $id, array $data): ?Shelf
    {
        $shelf = $this->shelfRepository->find($id);

        if (!$shelf) {
            return null;
        }

        $shelf->setPosition($data['position'] ?? $shelf->getPosition());

        if (isset($data['library'])) {
            $shelf->setLibrary($data['library']);
        }

        $this->entityManager->flush();

        return $shelf;
    }

    public function deleteShelf(int $id): bool
    {
        $shelf = $this->shelfRepository->find($id);

        if (!$shelf) {
            return false;
        }

        $this->entityManager->remove($shelf);
        $this->entityManager->flush();

        return true;
    }

    public function getShelf(int $id): ?Shelf
    {
        return $this->shelfRepository->find($id);
    }

    public function addBookToShelf(int $shelfId, Book $book): ?Shelf
    {
        $shelf = $this->shelfRepository->find($shelfId);

        if (!$shelf) {
            return null;
        }

        $shelf->addBook($book);
        $this->entityManager->flush();

        return $shelf;
    }

    public function removeBookFromShelf(int $shelfId, Book $book): ?Shelf
    {
        $shelf = $this->shelfRepository->find($shelfId);

        if (!$shelf) {
            return null;
        }

        $shelf->removeBook($book);
        $this->entityManager->flush();

        return $shelf;
    }

    public function getBooksByShelfId(int $shelfId): array
    {
        $shelf = $this->shelfRepository->find($shelfId);

        if (!$shelf) {
            return [];
        }

        return $shelf->getBooks()->toArray();
    }
}
