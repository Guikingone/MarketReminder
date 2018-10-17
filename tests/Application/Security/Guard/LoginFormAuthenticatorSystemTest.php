<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Application\Security\Guard;

use App\Application\Security\Guard\Interfaces\LoginFormAuthenticatorInterface;
use App\Application\Security\Guard\LoginFormAuthenticator;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Class LoginFormAuthenticatorSystemTest.
 *
 * @package App\Tests\Application\Security\Guard
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class LoginFormAuthenticatorSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var LoginFormAuthenticatorInterface|AbstractFormLoginAuthenticator|null
     */
    private $authenticator = null;

    /**
     * @var MessageBusInterface|null
     */
    private $messageBus = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var UrlGeneratorInterface|null
     */
    private $urlGenerator = null;

    /**
     * @var UserPasswordEncoderInterface|null
     */
    private $userPasswordEncoder = null;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->messageBus = static::$container->get('message_bus');
        $this->urlGenerator = static::$container->get('router')->getGenerator();
        $this->userPasswordEncoder = static::$container->get('security.password_encoder');

        $this->authenticator = new LoginFormAuthenticator(
            $this->messageBus,
            $this->urlGenerator,
            $this->userPasswordEncoder
        );
    }

    /**
     * @group Blackfire
     */
    public function testItSupports()
    {
        $request = Request::create('/fr/login', 'GET');

        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 150kB', 'LoginFormAuthenticator memory usage with support');

        $this->assertBlackfire($configuration, function() use ($request) {
            $this->authenticator->supports($request);
        });
    }

    /**
     * @group Blackfire
     */
    public function testItDoesNotSupports()
    {
        $request = Request::create('/fr', 'GET');

        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 20kB', 'LoginFormAuthenticator memory usage without support');

        $this->assertBlackfire($configuration, function() use ($request) {
            $this->authenticator->supports($request);
        });
    }
}
