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

namespace App\UI\Responder\Security;

use App\UI\Responder\Security\Interfaces\AskResetPasswordResponderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class AskResetPasswordResponder
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordResponder implements AskResetPasswordResponderInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        FormView $askResetPasswordTokenFormView = null,
        $isRedirect = false,
        $urlToRedirect = 'index',
        string $templateName = 'security/askResetPasswordToken.html.twig'
    ): Response {

        $isRedirect
            ? $response = new RedirectResponse($this->urlGenerator->generate($urlToRedirect))
            : $response = new Response(
                $this->twig->render($templateName, [
                    'askResetPasswordTokenForm' => $askResetPasswordTokenFormView
                ])
            );

        return $response->setCache([
            'public' => true,
            's_maxage' => 3600,
            'max_age' => 600
        ]);
    }
}
