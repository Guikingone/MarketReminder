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

namespace App\Application\Helper\Image\Interfaces;

/**
 * Interface ImageTypeCheckerHelperInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ImageTypeCheckerHelperInterface
{
    const ALLOWED_TYPES = ['image/png', 'image/jpeg'];

    /**
     * Allow to know if a file has the correct extension and can be managed.
     *
     * @param \SplFileInfo $uploadedFile
     *
     * @return bool    Whether or not the file has a correct extension.
     */
    public static function checkType(\SplFileInfo $uploadedFile): bool;
}
