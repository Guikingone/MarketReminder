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

namespace App\Helper;

use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\CloudStoragePersisterHelperInterface;

/**
 * Class ImageUploaderHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploaderHelper implements ImageUploaderHelperInterface
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $bucketName;

    /**
     * @var string
     */
    private $fileExtension;

    /**
     * @var bool
     */
    private $allowedToSave;

    /**
     * @var CloudStoragePersisterHelperInterface
     */
    private $cloudStoragePersister;

    /**
     * ImageUploaderHelper constructor.
     *
     * @param string $filePath
     * @param string $bucketName
     * @param CloudStoragePersisterHelperInterface $cloudStoragePersister
     */
    public function __construct(
        string $filePath,
        string $bucketName,
        CloudStoragePersisterHelperInterface $cloudStoragePersister
    ) {
        $this->filePath = $filePath;
        $this->bucketName = $bucketName;
        $this->cloudStoragePersister = $cloudStoragePersister;
    }

    /**
     * {@inheritdoc}
     */
    public function checkExtension(\SplFileInfo $uploadedFile): void
    {
        $this->fileExtension = $uploadedFile->guessExtension();

        switch ($this->fileExtension) {
            case !'.png' || !'.jpg':
                $this->allowedToSave = false;
                break;
            default:
                $this->allowedToSave = true;
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function store(\SplFileInfo $uploadedFile): ImageUploaderHelperInterface
    {
        $this->checkExtension($uploadedFile);

        if ($this->getAllowedToSave()) {
            $this->fileName = md5(str_rot13(uniqid())).'.'.$uploadedFile->guessExtension();

            $uploadedFile->move($this->filePath, $this->fileName);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(): ImageUploaderHelperInterface
    {
        $this->cloudStoragePersister
             ->persist(
                 $this->bucketName,
                 $this->filePath.'/'.$this->fileName,
                 ['name' => $this->fileName]
             );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedToSave(): bool
    {
        return $this->allowedToSave;
    }
}
