<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Resolvers;

use App\Domain\Models\User;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

/**
 * Class UserResolver.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserResolver implements ResolverInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * UserResolver constructor.
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
     * @return User|User[]|array|null|object
     */
    public function getUser(Argument $argument)
    {
        if ($argument->offsetExists('id')) {
            return [
                $this->entityManagerInterface->getRepository(User::class)
                                             ->findOneBy([
                                                 'id' => $argument->offsetGet('id'),
                                             ]),
            ];
        }

        return $this->entityManagerInterface->getRepository(User::class)->findAll();
    }
}
