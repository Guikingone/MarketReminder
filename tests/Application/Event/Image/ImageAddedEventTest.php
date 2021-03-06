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

namespace App\Tests\Domain\Event\Image;

use App\Application\Event\Image\ImageAddedEvent;
use App\Domain\Models\Interfaces\ImageInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageAddedEventTest;.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ImageAddedEventTest extends TestCase
{
    public function testGetterReturn()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $imageAddedEvent = new ImageAddedEvent($imageMock);

        static::assertInstanceOf(
            ImageInterface::class,
            $imageAddedEvent->getImage()
        );
    }
}
