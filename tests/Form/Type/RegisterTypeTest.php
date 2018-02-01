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

namespace tests\Form\Type;

use App\Builder\UserBuilder;
use App\Form\Type\RegisterType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use App\Subscriber\Form\ProfileImageSubscriber;
use App\Builder\Interfaces\UserBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;

/**
 * Class RegisterTypeTest
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeTest extends TypeTestCase
{
    /**
     * @var TranslatorInterface
     */
    private $translatorInterface;

    /**
     * @var UserBuilderInterface
     */
    private $userBuilderInterface;
    /**
     * @var ProfileImageSubscriberInterface
     */
    private $profileImageSubscriber;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->translatorInterface = $this->createMock(TranslatorInterface::class);

        $this->profileImageSubscriber = new ProfileImageSubscriber($this->translatorInterface);

        $this->userBuilderInterface = new UserBuilder();

        parent::setUp();
    }

    public function getExtensions()
    {
        $type = new RegisterType($this->profileImageSubscriber);

        return [
            new PreloadedExtension(
                [$type], []
            )
        ];
    }

    public function testDataSubmission()
    {
        $userBuilder = $this->userBuilderInterface
                            ->createUser()
                            ->withUsername('Tototo')
                            ->withEmail('toto@gmail.com')
                            ->withPlainPassword('Ie1FDLTOTO');

        $registerType = $this->factory->create(RegisterType::class, $userBuilder->getUser());

        static::assertTrue(
            $registerType->isSynchronized()
        );

        static::assertEquals(
            $userBuilder->getUser(),
            $registerType->getData()
        );
    }
}
