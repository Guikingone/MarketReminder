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

namespace App\Application\Symfony\Subscriber;

use App\Application\Symfony\Subscriber\Interfaces\ImageUploadSubscriberInterface;
use App\Helper\CloudVision\CloudVisionVoterHelper;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImageUploadSubscriber.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadSubscriber implements ImageUploadSubscriberInterface, EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyser;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * ImageUploadSubscriber constructor.
     *
     * @param TranslatorInterface                 $translator
     * @param ImageUploaderHelperInterface        $imageUploaderHelper
     * @param CloudVisionAnalyserHelperInterface  $cloudVisionAnalyser
     * @param ImageRetrieverHelperInterface       $imageRetrieverHelper
     * @param CloudVisionDescriberHelperInterface $cloudVisionDescriber
     */
    public function __construct(
        TranslatorInterface $translator,
        ImageUploaderHelperInterface $imageUploaderHelper,
        CloudVisionAnalyserHelperInterface $cloudVisionAnalyser,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        CloudVisionDescriberHelperInterface $cloudVisionDescriber
    ) {
        $this->translator = $translator;
        $this->imageUploaderHelper = $imageUploaderHelper;
        $this->cloudVisionAnalyser = $cloudVisionAnalyser;
        $this->imageRetrieverHelper = $imageRetrieverHelper;
        $this->cloudVisionDescriber = $cloudVisionDescriber;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $event): void
    {
        if (is_null($event->getData()['file'])) {
            return;
        }

        $this->imageUploaderHelper->store($event->getData());

        $analysedImage = $this->cloudVisionAnalyser
                              ->analyse(
                                  $this->imageUploaderHelper->getFilePath()
                                  .
                                  $this->imageUploaderHelper->getFileName(),
                                  'LABEL_DETECTION'
                              );

        $labels = $this->cloudVisionDescriber->describe($analysedImage)->labels();

        $this->cloudVisionDescriber->obtainLabel($labels);

        foreach ($this->cloudVisionDescriber->getLabels() as $label) {
            if (!CloudVisionVoterHelper::vote($label)) {
                $event->getForm()->addError(
                    new FormError(
                        $this->translator->trans(
                            'form.image.label_error', [], 'validators'
                        )
                    )
                );

                return;
            }
        }

        $this->imageUploaderHelper->upload();

        $imageRegistrationDTO = new ImageRegistrationDTO(
            $event->getForm()->get('alt')->getData(),
            $this->imageUploaderHelper->getFileName(),
            $this->imageUploaderHelper->getFilePath()
        );

        $event->getForm()->setData($imageRegistrationDTO);
    }
}