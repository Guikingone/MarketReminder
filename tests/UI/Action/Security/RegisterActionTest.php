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

use App\UI\Action\Security\RegisterAction;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Responder\Security\RegisterResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class RegisterActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterActionTest extends TestCase
{
    public function testReturn()
    {
        $requestMock = $this->createMock(Request::class);
        $twigMock = $this->createMock(Environment::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerResponder = new RegisterResponder($twigMock);

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $urlGeneratorMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(
            Response::class,
            $registerAction(
                $requestMock,
                $registerResponder
            )
        );
    }

    public function testHandlerProcess()
    {
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $registerTypeHandlerMock = $this->createMock(RegisterTypeHandlerInterface::class);
        $requestMock = $this->createMock(Request::class);
        $registerResponderMock = $this->createMock(RegisterResponder::class);
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('createView')->willReturn(new FormView());

        $registerTypeHandlerMock->method('handle')
                                ->willReturn(true);

        $urlGeneratorMock->method('generate')->willReturn('/fr/');

        $registerAction = new RegisterAction(
            $formFactoryMock,
            $urlGeneratorMock,
            $registerTypeHandlerMock
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $registerAction(
                $requestMock,
                $registerResponderMock
            )
        );
    }
}
