<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resolvers;

use App\Models\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

/**
 * Class StockResolver
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class StockResolver implements ResolverInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * StockResolver constructor.
     *
     * @param EntityManagerInterface $entityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * @param Argument $argument
     *
     * @return Stock|Stock[]|array|null|object
     */
    public function getStock(Argument $argument)
    {
        if ($argument->offsetExists('id')) {
            return $this->entityManagerInterface->getRepository(Stock::class)
                                                ->findOneBy([
                                                    'id' => $argument->offsetGet('id')
                                                ]);
        }

        return $this->entityManagerInterface->getRepository(Stock::class)->findAll();
    }
}
