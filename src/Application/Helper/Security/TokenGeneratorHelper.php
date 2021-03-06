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

namespace App\Application\Helper\Security;

use App\Application\Helper\Security\Interfaces\TokenGeneratorHelperInterface;

/**
 * Class TokenGeneratorHelper
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class TokenGeneratorHelper implements TokenGeneratorHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public static function generateResetPasswordToken(string $username, string $email): string
    {
        return substr(
            crypt(md5(str_rot13($username)), $email),
            0,
            10
        );
    }
}
