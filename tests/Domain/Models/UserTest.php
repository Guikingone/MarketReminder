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

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserTest extends TestCase
{
    public function testItImplementsAndReturnData()
    {
        $user = new User(
            'toto@gmail.com',
            'Toto',
            'Ie1FDLTOTO',
            'aa194daz4dz24ad4zd9d9adza4d9d9a'
        );

        static::assertInstanceOf(UserInterface::class, $user);
        static::assertSame('toto@gmail.com', $user->getEmail());
        static::assertSame('Toto', $user->getUsername());
        static::assertSame('Ie1FDLTOTO', $user->getPassword());
        static::assertSame('aa194daz4dz24ad4zd9d9adza4d9d9a', $user->getValidationToken());
    }

    public function testImageRelation()
    {
        $image = $this->createMock(ImageInterface::class);
        $image->method('getAlt')->willReturn('toto.png');

        $user = new User(
            'toto@gmail.com',
            'Toto',
            'Ie1FDLTOTO',
            'aa194daz4dz24ad4zd9d9adza4d9d9a',
            $image
        );

        static::assertInstanceOf(ImageInterface::class, $user->getProfileImage());
    }
}
