<?php

declare(strict_types=1);

namespace Apb\UserBundle\Enum;

abstract class GlobalEnum
{
    public const TIMESTAMP_ONE_DAY = 24 * 60 * 60;

    public const MODE_CREATE = 'mode_create';
    public const MODE_EDIT = 'mode_edit';

    public const LANG_DEFAULT = 'fr';
    public const LANG_FR = 'fr';
    public const LANG_EN = 'en';
    public const LANG_DE = 'de';

    public const DIRECTION_UP = 'direction_up';
    public const DIRECTION_DOWN = 'direction_down';
    public const DIRECTION_EQUAL = 'direction_equal';

    public const ORIGIN_DASHBOARD = 'origin_dashboard';
    public const ORIGIN_APP = 'origin_app';

    /**
     * @return string[]
     */
    public static function getLanguageTypes(): array
    {
        return [
            self::LANG_FR,
            self::LANG_EN,
            self::LANG_DE,
        ];
    }

    /**
     * @return string[]
     */
    public static function getLanguageChoices(): array
    {
        return array_combine(self::getLanguageTypes(), self::getLanguageTypes());
    }

    /**
     * @return string[]
     */
    public static function getOriginChoices(): array
    {
        return array_combine(self::getOriginTypes(), self::getOriginTypes());
    }

    /**
     * @return string[]
     */
    private static function getOriginTypes(): array
    {
        return [
            self::ORIGIN_APP,
            self::ORIGIN_DASHBOARD,
        ];
    }
}
