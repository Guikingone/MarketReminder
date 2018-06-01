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
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Psr\Cache\CacheItemInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class CloudTranslationWriter.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWriter implements CloudTranslationWriterInterface
{
    /**
     * @var CloudTranslationBackupWriterInterface
     */
    private $cloudTranslationBackupWriter;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var array
     */
    private $entries;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudTranslationBackupWriterInterface $cloudTranslationBackupWriter,
        ConnectorInterface $connector
    ) {
        $this->cloudTranslationBackupWriter = $cloudTranslationBackupWriter;
        $this->connector = $connector;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $locale, string $channel, string $fileName, array $values): bool
    {
        $cacheItem = $this->connector->getAdapter()->getItem($fileName);

        if ($cacheItem->isHit()) {
            if (!$this->isCacheContentValid($cacheItem, $values)) {

                $this->connector->getAdapter()->invalidateTags($cacheItem->getPreviousTags());

                $this->connector->getAdapter()->deleteItem($cacheItem->getKey());

                return $this->write($locale, $channel, $fileName, $values);
            }

            return false;
        }

        $tag = Uuid::uuid4()->toString();

        foreach ($values as $item => $value) {
            $this->entries[] = new CloudTranslationItem([
                '_locale' => $locale,
                'channel' => $channel,
                'tag' => $tag,
                'key' => $item,
                'value' => $value
            ]);
        }

        $cacheItem->set($this->entries);
        $cacheItem->tag($tag);

        if (!$this->cloudTranslationBackupWriter->warmBackUp($channel, $locale, $values)) {
            // If the backup is fresh, the process continue.
        }

        return $this->connector->getAdapter()->save($cacheItem);
    }

    /**
     * {@inheritdoc}
     */
    public function isCacheContentValid(CacheItemInterface $cacheValues, array $values): bool
    {
        $translationKey = [];
        $translationContent = [];
        $toCheckKey = [];
        $toCheckContent = [];

        foreach ($cacheValues->get() as $item => $value) {
            $translationKey[] = $value->getKey();
            $translationContent[] = $value->getValue();
        }

        foreach ($values as $item => $value) {
            $toCheckKey[] = $item;
            $toCheckContent[] = $value;
        }

        $finalArray = array_combine($translationKey, $translationContent);
        $finalCheckArray = array_combine($toCheckKey, $toCheckContent);

        return \count(array_diff($finalArray, $finalCheckArray)) > 0 ? false : true;
    }
}
