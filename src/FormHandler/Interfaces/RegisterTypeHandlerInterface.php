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

namespace App\FormHandler\Interfaces;

use Symfony\Component\Form\FormInterface;

/**
 * Interface RegisterTypeHandlerInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RegisterTypeHandlerInterface
{
    /**
     * @param FormInterface $registerForm  The RegisterType Form
     *
     * @return bool  If the handling process has succeed
     */
    public function handle(FormInterface $registerForm): bool;
}
