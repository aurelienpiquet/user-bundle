<?php

declare(strict_types=1);

namespace Apb\UserBundle\Enum;

abstract class UserEnum
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    public const GENDER_MALE = 'gender_male';
    public const GENDER_FEMALE = 'gender_female';
    public const GENDER_NON_BINARY = 'gender_non_binary';

    public const CONSENT_CONTACT = 'consent_contact';
    public const CONSENT_SAME_TEAM = 'consent_same_team';
    public const CONSENT_NEW_TEAM = 'consent_new_team';

    /**
     * @return string[]
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }

    /**
     * @return string[]
     */
    public static function getRoleChoices(): array
    {
        return array_combine(self::getRoles(), self::getRoles());
    }

    /**
     * @return string[]
     */
    public static function getGenderTypes(): array
    {
        return [
            self::GENDER_FEMALE,
            self::GENDER_MALE,
            self::GENDER_NON_BINARY,
        ];
    }

    /**
     * @return string[]
     */
    public static function getGenderChoices(): array
    {
        return array_combine(self::getGenderTypes(), self::getGenderTypes());
    }

    /**
     * @return string[]
     */
    public static function getConsentChoices(): array
    {
        return array_combine(self::getConsentTypes(), self::getConsentTypes());
    }


    /**
     * @return string[]
     */
    public static function getConsentTypes(): array
    {
        return [
            self::CONSENT_CONTACT,
            self::CONSENT_SAME_TEAM,
        ];
    }

    /**
     * @return string[]
     */
    public static function getExportableConsentTypes(): array
    {
        return [
            self::CONSENT_SAME_TEAM,
        ];
    }

    public static function getReadableGender(?string $gender): string
    {
        return match ($gender) {
            self::GENDER_MALE => 'M',
            self::GENDER_FEMALE => 'F',
            self::GENDER_NON_BINARY => 'NB',
            default => '-',
        };
    }

    /**
     * @return int[]
     */
    public static function getActivityGaps(): array
    {
        return [5, 10, 30];
    }
}
