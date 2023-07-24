<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testFind(): void
    {
        $user = $this->userRepository->find(7);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testFindByEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setUsername('johndoe');
        $user->setPassword('$2y$13$yL344gNdsLTKoMJ/4BKRY.9g2X.2NSxwYVetwla8I1AyhbdiPI6fS');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $foundUser = $this->userRepository->findOneBy(['email' => 'test@example.com']);
        $this->assertSame('test@example.com', $foundUser->getEmail());
        $this->assertSame('John', $foundUser->getFirstname());
        $this->assertSame('Doe', $foundUser->getLastname());
        $this->assertSame('johndoe', $foundUser->getUsername());
        $this->assertSame('$2y$13$yL344gNdsLTKoMJ/4BKRY.9g2X.2NSxwYVetwla8I1AyhbdiPI6fS', $foundUser->getPassword());
    }

    public function testAddAndRemove(): void
    {
        // Create a new User object and set required properties
        $user = new User();
        $user->setEmail('test1@example.com'); // Set a valid email value
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setUsername('johndoe');
        $user->setPassword('$2y$13$yL344gNdsLTKoMJ/4BKRY.9g2X.2NSxwYVetwla8I1AyhbdiPI6fS');

        // Persist and flush the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retrieve the user by email from the repository
        $foundUser = $this->userRepository->findOneBy(['email' => 'test1@example.com']);

        // Assert that the retrieved user matches the one we created
        $this->assertSame('test1@example.com', $foundUser->getEmail());
        $this->assertSame('John', $foundUser->getFirstname());
        $this->assertSame('Doe', $foundUser->getLastname());
        $this->assertSame('johndoe', $foundUser->getUsername());
        $this->assertSame('$2y$13$yL344gNdsLTKoMJ/4BKRY.9g2X.2NSxwYVetwla8I1AyhbdiPI6fS', $foundUser->getPassword());

        // Remove the user from the database
        $this->entityManager->remove($foundUser);
        $this->entityManager->flush();

        // Assert that the user has been successfully removed
        $removedUser = $this->userRepository->findOneBy(['email' => 'test1@example.com']);
        $this->assertNull($removedUser);
    }
}
