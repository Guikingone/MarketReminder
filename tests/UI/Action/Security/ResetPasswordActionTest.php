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

namespace App\Tests\UI\Action\Security;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ResetPasswordActionInterface;
use App\UI\Action\Security\ResetPasswordAction;
use App\UI\Form\FormHandler\Interfaces\ResetPasswordTypeHandlerInterface;
use App\UI\Responder\Security\ResetPasswordResponder;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class ResetPasswordActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordActionTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormInterface
     */
    private $formInterface;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ResetPasswordPresenterInterface
     */
    private $resetPasswordPresenter;

    /**
     * @var ResetPasswordTypeHandlerInterface
     */
    private $resetPasswordTypeHandler;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->formInterface = $this->createMock(FormInterface::class);
        $this->resetPasswordPresenter = $this->createMock(ResetPasswordPresenterInterface::class);
        $this->resetPasswordTypeHandler = $this->createMock(ResetPasswordTypeHandlerInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->formFactory->method('create')
                          ->willReturn($this->formInterface);

        $this->formInterface->method('handleRequest')->willReturnSelf();

        $this->resetPasswordPresenter = $this->getMockBuilder(ResetPasswordPresenter::class)
                                             ->enableOriginalConstructor()
                                             ->getMock();

        $this->resetPasswordPresenter->method('getNotificationMessage')
                                     ->willReturn([
                                         'content' => '',
                                         'type' => ''
                                     ]);

        $this->request = Request::create('/fr/reset-password/dadddad', 'POST');
        $this->request->initialize([], [], ['token' => 'dadddad']);

        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->resetPasswordTypeHandler,
            $this->userRepository
        );

        static::assertInstanceOf(
            ResetPasswordActionInterface::class,
            $resetPasswordAction
        );
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     */
    public function testBlackfireProfilingDuringWrongProcess()
    {
        $this->userRepository->method('getUserByResetPasswordToken')->willReturn(null);

        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->resetPasswordTypeHandler,
            $this->userRepository
        );

        $probe = static::$blackfire->createProbe();

        $resetPasswordAction($this->request, $resetPasswordResponder);

        static::$blackfire->endProbe($probe);
    }

    public function testItReturnDuringWrongProcess()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->resetPasswordTypeHandler,
            $this->userRepository
        );

        $this->userRepository->method('getUserByResetPasswordToken')->willReturn(null);

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordAction($this->request, $resetPasswordResponder)
        );
    }

    public function testItReturnDuringRightProcess()
    {
        $resetPasswordResponder = new ResetPasswordResponder(
            $this->twig,
            $this->resetPasswordPresenter,
            $this->urlGenerator
        );

        $resetPasswordAction = new ResetPasswordAction(
            $this->eventDispatcher,
            $this->formFactory,
            $this->resetPasswordPresenter,
            $this->resetPasswordTypeHandler,
            $this->userRepository
        );

        $this->resetPasswordTypeHandler->method('handle')->willReturn(true);

        $this->userRepository->method('getUserByResetPasswordToken')->willReturn(
            $this->createMock(UserInterface::class)
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordAction($this->request, $resetPasswordResponder)
        );
    }
}
