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

namespace App\Tests\UI\Form\Type;

use App\Builder\Interfaces\ImageBuilderInterface;
use App\Builder\Interfaces\UserBuilderInterface;
use App\Builder\UserBuilder;
use App\Helper\Interfaces\CloudVision\CloudVisionAnalyserHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionDescriberHelperInterface;
use App\Helper\Interfaces\CloudVision\CloudVisionVoterHelperInterface;
use App\Helper\Interfaces\Image\ImageUploaderHelperInterface;
use App\Helper\Interfaces\Image\ImageRetrieverHelperInterface;
use App\Helper\Interfaces\Image\ImageTypeCheckerHelperInterface;
use App\Subscriber\Interfaces\ImageUploadSubscriberInterface;
use App\Subscriber\Form\ImageUploadSubscriber;
use App\UI\Form\Type\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class RegisterTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeTest extends TypeTestCase
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var ImageBuilderInterface
     */
    private $imageBuilder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ImageTypeCheckerHelperInterface
     */
    private $imageTypeChecker;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelper;

    /**
     * @var ImageUploadSubscriberInterface
     */
    private $profileImageSubscriber;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoterHelper;

    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyserHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriberHelper;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->imageBuilder = $this->createMock(ImageBuilderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->imageTypeChecker = $this->createMock(ImageTypeCheckerHelperInterface::class);
        $this->imageUploaderHelper = $this->createMock(ImageUploaderHelperInterface::class);
        $this->imageRetrieverHelper = $this->createMock(ImageRetrieverHelperInterface::class);
        $this->cloudVisionVoterHelper = $this->createMock(CloudVisionVoterHelperInterface::class);
        $this->cloudVisionAnalyserHelper = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $this->cloudVisionDescriberHelper = $this->createMock(CloudVisionDescriberHelperInterface::class);

        $this->profileImageSubscriber = new ImageUploadSubscriber(
                                            $this->translator,
                                            $this->imageBuilder,
                                            $this->imageUploaderHelper,
                                            $this->cloudVisionAnalyserHelper,
                                            $this->imageRetrieverHelper,
                                            $this->cloudVisionDescriberHelper,
                                            $this->cloudVisionVoterHelper,
                                            $this->imageTypeChecker
                                        );

        $this->userBuilder = new UserBuilder();

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        $type = new RegisterType($this->profileImageSubscriber);

        return [
            new PreloadedExtension(
                [$type],
                []
            ),
        ];
    }

    public function testDataSubmission()
    {
        $userBuilder = $this->userBuilder
                            ->createUser()
                            ->withUsername('Tototo')
                            ->withEmail('toto@gmail.com')
                            ->withPlainPassword('Ie1FDLTOTO');

        $registerType = $this->factory->create(RegisterType::class, $userBuilder->getUser());

        static::assertTrue(
            $registerType->isSynchronized()
        );

        static::assertEquals(
            $userBuilder->getUser(),
            $registerType->getData()
        );
    }
}