<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Mutators;

use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

/**
 * Class StockMutator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class StockMutator implements MutationInterface
{
    public function createStock(Argument $argument)
    {
    }

    public function updateStock(Argument $argument)
    {
    }

    public function dropStock(Argument $argument)
    {
    }
}
