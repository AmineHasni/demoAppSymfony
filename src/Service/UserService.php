<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllUsers()
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function getUserById($id)
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function createUser($name, $age)
    {
        $user = new User();
        $user->setName($name);
        $user->setAge($age);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function updateUser(User $user, $name, $age)
    {
        $user->setName($name);
        $user->setAge($age);
        $this->entityManager->flush();
        return $user;
    }

    public function deleteUser(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}