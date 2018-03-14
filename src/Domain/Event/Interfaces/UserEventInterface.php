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

namespace App\Domain\Event\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserEventInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserEventInterface
{
    /**
     * UserEventInterface constructor.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user);

    /**
     * Allow to access the user instance.
     *
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}
