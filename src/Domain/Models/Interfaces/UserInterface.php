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

namespace App\Domain\Models\Interfaces;

use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface UserInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserInterface
{
    /**
     * UserInterface constructor.
     *
     * @param string              $email            The email linked to this user.
     * @param string              $username         The username used by the User.
     * @param string              $password         The encrypted version of the password.
     * @param string              $validationToken  The validation token used for validate the user.
     * @param ImageInterface|null $profileImage     If the user define it, the profile image.
     *
     * This constructor should generate an UUID, the creation date, define if the User is
     * active and validated then define the default role ('ROLE_USER').
     */
    public function __construct(
        string $email,
        string $username,
        string $password,
        string $validationToken,
        ImageInterface $profileImage = null
    );

    /**
     * Allow to store the reset password token once it's generated.
     *
     * @param UserResetPasswordToken $userPasswordReset
     */
    public function askForPasswordReset(UserResetPasswordToken $userPasswordReset): void;

    /**
     * Allow to update the password, for simplicity, a "resetDate" is define in order to help the user.
     *
     * @param string $newPassword
     */
    public function updatePassword(string $newPassword): void;

    /**
     * Allow to mark the user as validated once he has validate his account via a token.
     */
    public function validate(): void;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return null|string
     */
    public function getUsername(): ? string;

    /**
     * @return null|string
     */
    public function getEmail(): ? string;

    /**
     * @return null|string
     */
    public function getPassword(): ? string;

    /**
     * @return array
     */
    public function getRoles(): ? array;

    /**
     * @return bool
     */
    public function getActive(): ? bool;

    /**
     * @return \DateTime
     */
    public function getCreationDate(): ? \DateTime;

    /**
     * @return array
     */
    public function getCurrentState(): ? array;

    /**
     * @return bool
     */
    public function getValidated(): ? bool;

    /**
     * @return null|string
     */
    public function getValidationToken(): ? string;

    /**
     * @return int|null
     */
    public function getValidationDate(): ?int;

    /**
     * @return null|string
     */
    public function getResetPasswordToken():? string;

    /**
     * @return null|int
     */
    public function getAskResetPasswordDate():? int;

    /**
     * @return int|null
     */
    public function getResetPasswordDate():? int;

    /**
     * @param ImageInterface $profileImage
     */
    public function setProfileImage(ImageInterface $profileImage): void;

    /**
     * @return null|ImageInterface
     */
    public function getProfileImage():? ImageInterface;
}
