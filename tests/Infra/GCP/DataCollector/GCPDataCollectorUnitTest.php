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

namespace App\Tests\Infra\GCP\DataCollector;

use App\Infra\GCP\DataCollector\GCPDataCollector;
use App\Infra\GCP\DataCollector\Interfaces\GCPDataCollectorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * Class GCPDataCollectorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class GCPDataCollectorUnitTest extends TestCase
{
    public function testItImplements()
    {
        $gcpDataCollector = new GCPDataCollector();

        static::assertInstanceOf(
            DataCollectorInterface::class,
            $gcpDataCollector
        );
        static::assertInstanceOf(
            GCPDataCollectorInterface::class,
            $gcpDataCollector
        );
    }
}
