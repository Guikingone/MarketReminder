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

namespace App\Application\Event\User;

use App\Application\Event\User\Interfaces\UserEventInterface;
use App\Domain\Models\Interfaces\UserInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractUserEvent.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class AbstractUserEvent extends Event implements UserEventInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
