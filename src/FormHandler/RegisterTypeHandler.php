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

namespace App\FormHandler;

use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Models\Interfaces\RegisteredUserInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;

/**
 * Class RegisterTypeHandler
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandler implements RegisterTypeHandlerInterface
{
    /**
     * @var ObjectManager
     */
    private $documentManagerInterface;

    /**
     * RegisterTypeHandler constructor.
     *
     * @param ObjectManager $documentManagerInterface
     */
    public function __construct(ObjectManager $documentManagerInterface)
    {
        $this->documentManagerInterface = $documentManagerInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm, RegisteredUserInterface $registeredUser): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            return true;
        }

        return false;
    }
}
