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

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationBackupWriterUnitTest.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationBackupWriterUnitTest extends TestCase
{
    /**
     * @var ConnectorInterface
     */
    private $fileSystemBackupConnector;

    /**
     * @var ConnectorInterface
     */
    private $redisBackupConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fileSystemBackupConnector = $this->createMock(ConnectorInterface::class);
        $this->redisBackupConnector = $this->createMock(ConnectorInterface::class);
    }

    public function testItImplements()
    {
        $cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->fileSystemBackupConnector);

        static::assertInstanceOf(
            CloudTranslationBackupWriterInterface::class,
            $cloudTranslationBackupWriter
        );
    }
}