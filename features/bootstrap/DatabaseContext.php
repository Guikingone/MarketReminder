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

use App\Domain\Models\User;
use App\Domain\UseCase\UserRegistration\DTO\UserRegistrationDTO;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * Class DatabaseContext
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class DatabaseContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * DatabaseContext constructor.
     *
     * @param RegistryInterface        $doctrine
     * @param KernelInterface          $kernel
     */
    public function __construct(
        RegistryInterface $doctrine,
        KernelInterface $kernel
    ) {
        $this->doctrine = $doctrine;
        $this->kernel = $kernel;
        $this->schemaTool = new SchemaTool($this->doctrine->getManager());
    }

    /**
     * @BeforeScenario
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function clearDatabase()
    {
        $this->schemaTool->dropSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
        $this->schemaTool->createSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
    }

    /**
     * Launch Doctrine fixtures command
     *
     * @param TableNode $users
     *
     * @Given /^I load following users:$/
     *
     * @throws Exception
     */
    public function iLoadFollowingUsers(TableNode $users)
    {
        $encoder = new BCryptPasswordEncoder(13);

        foreach ($users->getHash() as $hash) {

            $userDTO = new UserRegistrationDTO(
                $hash['username'],
                $hash['email'],
                $hash['plainPassword'],
                $hash['validationToken']
            );

            $user = new User(
                $userDTO->email,
                $userDTO->username,
                $hash['plainPassword'],
                Closure::fromCallable([$encoder, 'encodePassword']),
                $userDTO->validationToken
            );

            if ($hash['validated']) {
                $user->validate();
            }

            $this->doctrine->getManager()->persist($user);
        }

        $this->doctrine->getManager()->flush();
    }
}
