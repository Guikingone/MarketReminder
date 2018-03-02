<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Repository;

use App\Interactor\UserInteractor;
use App\Models\Interfaces\UserInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserRepositoryTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
                                              ->get('doctrine.orm.entity_manager');
    }

    public function testInterfaceImplementation()
    {
        static::assertInstanceOf(
            UserRepositoryInterface::class,
            $this->entityManager->getRepository(UserInteractor::class)
        );
    }

    public function testUserIsLoadedUsingUsername()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(UserInteractor::class)
                                ->loadUserByUsername('Toto')
        );
    }

    public function testUserIsLoadedUsingEmail()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(UserInteractor::class)
                                ->loadUserByUsername('toto@gmail.com')
        );
    }

    public function testUserIsNotLoadedUsingUsername()
    {
        static::assertNull(
            $this->entityManager->getRepository(UserInteractor::class)
                                ->loadUserByUsername('Tutu')
        );
    }

    public function testUserIsNotLoadedUsingEmail()
    {
        static::assertNull(
            $this->entityManager->getRepository(UserInteractor::class)
                                ->loadUserByUsername('tutu@gmail.com')
        );
    }

    public function testUserIsNotFoundByUsernameAndEmail()
    {
        static::assertNull(
            $this->entityManager->getRepository(UserInteractor::class)
                                ->getUserByUsernameAndEmail('Titi', 'titi@gmail.com')
        );
    }

    public function testUserIsFoundByUsernameAndEmail()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(UserInteractor::class)
                                ->getUserByUsernameAndEmail('Toto', 'toto@gmail.com')
        );
    }

    public function testUserIsReturnedByUsername()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(UserInteractor::class)
                                ->getUserByUsername('Toto')
        );
    }

    public function testUserIsReturnedByEmail()
    {
        static::assertInstanceOf(
            UserInterface::class,
            $this->entityManager->getRepository(UserInteractor::class)
                                ->getUserByEmail('toto@gmail.com')
        );
    }
}
