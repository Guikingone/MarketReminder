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

namespace App\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\FileSystemConnectorInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class FileSystemConnector.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class FileSystemConnector implements FileSystemConnectorInterface, ConnectorInterface
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter(): TagAwareAdapterInterface
    {
        return new TagAwareAdapter(
            new FilesystemAdapter($this->namespace)
        );
    }
}
