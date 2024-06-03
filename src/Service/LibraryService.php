<?php
namespace App\Service;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\ORM\EntityManagerInterface;

class LibraryService
{
    private $entityManager;
    private $libraryRepository;

    public function __construct(EntityManagerInterface $entityManager, LibraryRepository $libraryRepository)
    {
        $this->entityManager = $entityManager;
        $this->libraryRepository = $libraryRepository;
    }

    public function getAllLibraries()
    {
        return $this->libraryRepository->findAll();
    }

    public function getLibraryById(int $id)
    {
        return $this->libraryRepository->find($id);
    }

    public function addLibrary(Library $library)
    {
        $this->entityManager->persist($library);
        $this->entityManager->flush();
    }

    public function updateLibrary(Library $library)
    {
        $this->entityManager->persist($library);
        $this->entityManager->flush();
    }

    public function deleteLibrary(Library $library)
    {
        $this->entityManager->remove($library);
        $this->entityManager->flush();
    }
}