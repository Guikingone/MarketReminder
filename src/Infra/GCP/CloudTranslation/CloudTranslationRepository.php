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

namespace App\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Class CloudTranslationRepository.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationRepository implements CloudTranslationRepositoryInterface
{
    /**
     * @var ConnectorInterface
     */
    private $backUpConnector;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var CloudTranslationItemInterface
     */
    private $cacheEntry;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ConnectorInterface $connector,
        ConnectorInterface $backUpConnector
    ) {
        $this->connector = $connector;
        $this->backUpConnector = $backUpConnector;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries(string $filename): ?array
    {
        $cacheItem = $this->connector->getAdapter()->getItem($filename);

        if ($cacheItem instanceof CacheItemInterface && $cacheItem->isHit()) {
            return $cacheItem->get();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleEntry(string $filename, string $locale, string $key): ?CloudTranslationItemInterface
    {
        $cacheItem = $this->connector->getAdapter()->getItem($filename);

        if ($cacheItem->isHit()) {
            foreach ($cacheItem->get() as $item => $value) {
                if ($value->getKey() === $key && $value->getLocale() === $locale) {
                    $this->cacheEntry = $value;
                }
            }

            return $this->cacheEntry;
        }

        return null;
    }
}
