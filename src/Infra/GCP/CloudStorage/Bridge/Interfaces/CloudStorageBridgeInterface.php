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

namespace App\Infra\GCP\CloudStorage\Bridge\Interfaces;

use Google\Cloud\Storage\StorageClient;

/**
 * Interface CloudStorageBridgeInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudStorageBridgeInterface
{
    /**
     * @return StorageClient
     */
    public function getStorageClient(): StorageClient;
}
