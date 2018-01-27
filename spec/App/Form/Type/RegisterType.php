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

namespace spec\App\Form\Type;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormTypeInterface;

/**
 * Class RegisterType
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterType extends ObjectBehavior
{
    public function it_implement()
    {
        $this->shouldImplement(FormTypeInterface::class);
    }
}