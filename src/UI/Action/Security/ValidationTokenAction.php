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

namespace App\UI\Action\Security;

use App\Application\Event\SessionMessageEvent;
use App\Application\Event\UserEvent;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ValidationTokenActionInterface;
use App\UI\Responder\Security\Interfaces\ValidationTokenResponderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ValidationTokenAction;
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="web_validation",
 *     path="/validation/{token}",
 *     methods={"GET"}
 * )
 */
class ValidationTokenAction implements ValidationTokenActionInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param ValidationTokenResponderInterface $responder
     *
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        ValidationTokenResponderInterface $responder
    ): RedirectResponse {

        if (!$request->attributes->get('token')) {

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'failure',
                    'security.validation_failure.notFound_token'
                )
            );

            return $responder();
        }

        if (!$user = $this->userRepository->getUserByToken($request->attributes->get('token'))) {
            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'failure',
                    'security.validation_failure.notFound_token'
                )
            );

            return $responder();
        }

        $user->validate();

        $this->userRepository->flush();

        $this->eventDispatcher->dispatch(
            UserEvent::USER_VALIDATED,
            new UserEvent($user)
        );

        $this->eventDispatcher->dispatch(
            SessionMessageEvent::NAME,
            new SessionMessageEvent(
                'success',
                'security.validation_success'
            )
        );

        return $responder();
    }
}
