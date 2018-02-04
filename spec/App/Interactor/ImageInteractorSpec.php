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

namespace spec\App\Interactor;

use PhpSpec\ObjectBehavior;
use App\Models\Interfaces\ImageInterface;

/**
 * Class ImageInteractorSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageInteractorSpec extends ObjectBehavior
{
    public function it_implement()
    {
        $this->shouldImplement(ImageInterface::class);
    }
}
