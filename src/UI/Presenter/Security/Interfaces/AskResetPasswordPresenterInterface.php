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

namespace App\UI\Presenter\Security\Interfaces;

use Symfony\Component\Form\FormView;

/**
 * Interface AskResetPasswordPresenterInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface AskResetPasswordPresenterInterface
{
    /**
     * @return array
     */
    public function getCard(): array;

    /**
     * @return null|FormView
     */
    public function getForm(): ?FormView;
}
