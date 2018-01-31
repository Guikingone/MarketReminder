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

namespace App\Models;

use App\Models\Interfaces\ImageInterface;

/**
 * Class Image.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class Image implements ImageInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $creationDate;

    /**
     * @var \DateTime
     */
    protected $modificationDate;

    /**
     * @var string
     */
    protected $alt;

    /**
     * @var string
     */
    protected $url;

    /**
     * {@inheritdoc}
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationDate(): string
    {
        return $this->creationDate->format('d-m-Y');
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getModificationDate(): ? string
    {
        return $this->modificationDate->format('d-m-Y');
    }

    /**
     * {@inheritdoc}
     */
    public function setModificationDate(\DateTime $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlt():? string
    {
        return $this->alt;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl():? string
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
