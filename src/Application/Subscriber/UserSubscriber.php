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

namespace App\Application\Subscriber;

use App\Application\Event\UserEvent;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

/**
 * Class UserSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class UserSubscriber implements EventSubscriberInterface, UserSubscriberInterface
{
    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $emailSender,
        \Swift_Mailer $swiftMailer,
        Environment $twig,
        PresenterInterface $presenter
    ) {
        $this->emailSender = $emailSender;
        $this->swiftMailer = $swiftMailer;
        $this->twig = $twig;
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvent::USER_ASK_RESET_PASSWORD => 'onUserAskResetPasswordEvent',
            UserEvent::USER_CREATED => 'onUserCreated',
            UserEvent::USER_RESET_PASSWORD => 'onUserResetPassword',
            UserEvent::USER_VALIDATED => 'onUserValidated'
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserAskResetPasswordEvent(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $event->getLocale(),
            'page' => [
                'user' => $event->getUser(),
                'content' => [
                    'key' => 'user.ask_reset_password.content',
                    'channel' => 'messages'
                ],
                'link' => [
                    'key' => 'user.ask_reset_password.link.text',
                    'channel' => 'messages'
                ],
                'header' => [
                    'key' => 'user.ask_reset_password.header',
                    'channel' => 'messages'
                ],
                'subject' => [
                    'key' => 'user.ask_reset_password.header',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $askResetPasswordMail = (new \Swift_Message)
            ->setSubject($this->presenter->getPage()['subject']['value'])
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getPage()['user']->getEmail())
            ->setBody(
                $this->twig->render('emails/security/user_ask_reset_password.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($askResetPasswordMail);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserCreated(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $event->getLocale(),
            'page' => [
                'user' => $event->getUser(),
                'content_first' => [
                    'key' => 'user.registration.welcome.content_first_part',
                    'channel' => 'messages'
                ],
                'content_second' => [
                    'key' => 'user.registration.welcome.content_second_part',
                    'channel' => 'messages'
                ],
                'link' => [
                    'key' => 'user.registration.welcome.content.link.text',
                    'channel' => 'messages'
                ],
                'header' => [
                    'key' => 'user.registration.welcome.header',
                    'channel' => 'messages'
                ],
                'subject' => [
                    'key' => 'user.registration.welcome.header',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $registrationMail = (new \Swift_Message)
            ->setSubject($this->presenter->getPage()['subject']['value'])
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getPage()['user']->getEmail())
            ->setBody(
                $this->twig->render('emails/security/registration_mail.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($registrationMail);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserResetPassword(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $event->getLocale(),
            'page' => [
                'user' => $event->getUser(),
                'body' => [
                    'key' => 'user.reset_password.content',
                    'channel' => 'messages'
                ],
                'link' => [
                    'key' => 'security.login',
                    'channel' => 'messages'
                ],
                'header' => [
                    'key' => 'user.reset_password.header',
                    'channel' => 'messages',
                ],
                'subject' => [
                    'key' => 'user.reset_password.header',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $resetPasswordMessage = (new \Swift_Message)
            ->setSubject($this->presenter->getPage()['subject']['value'])
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getPage()['user']->getEmail())
            ->setBody(
                $this->twig->render('emails/security/user_reset_password.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($resetPasswordMessage);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onUserValidated(UserEvent $event): void
    {
        $this->presenter->prepareOptions([
            '_locale' => $event->getLocale(),
            'page' => [
                'user' => $event->getUser(),
                'content' => [
                    'key' => '',
                    'channel' => 'messages'
                ],
                'header' => [
                    'key' => '',
                    'channel' => 'messages'
                ],
                'subject' => [
                    'key' => '',
                    'channel' => 'messages'
                ]
            ]
        ]);

        $validationMail = (new \Swift_Message)
            ->setSubject($this->presenter->getPage()['subject']['value'])
            ->setFrom($this->emailSender)
            ->setTo($this->presenter->getPage()['user']->getEmail())
            ->setBody(
                $this->twig->render('emails/security/validation_mail.html.twig', [
                    'presenter' => $this->presenter
                ]), 'text/html'
            );

        $this->swiftMailer->send($validationMail);
    }
}
