<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infra\GCP\Bridge\Interfaces;

/**
 * Interface CloudTranslationBridgeInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudTranslationBridgeInterface extends CloudBridgeInterface
{
    /**
     * CloudTranslationBridgeInterface constructor.
     *
     * @param string  $translationCredentialsFolder
     */
    public function __construct(string $translationCredentialsFolder);
}
