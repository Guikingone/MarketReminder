<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mutators;

use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

/**
 * Class UserMutator
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserMutator implements MutationInterface
{
    public function register(array $credentials)
    {

    }

    public function login(array $credentials)
    {

    }

    public function forgotPassword(array $credentials)
    {

    }

    public function dropUser(array $credentials)
    {

    }
}
