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

namespace tests\Domain\Models;

use App\Domain\Models\Image;
use App\Domain\Models\Interfaces\ImageInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageTest extends TestCase
{
    public function testDataAreAvailable()
    {
        $image = new Image('toto', 'toto.png', 'http://tests.com/profileImage/toto.png');

        static::assertInstanceOf(ImageInterface::class, $image);
        static::assertSame('toto', $image->getAlt());
        static::assertSame('toto.png', $image->getFilename());
        static::assertSame('http://tests.com/profileImage/toto.png', $image->getPublicUrl());
    }
}
