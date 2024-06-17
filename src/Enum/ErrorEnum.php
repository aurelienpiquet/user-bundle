<?php

declare(strict_types=1);

namespace Apb\UserBundle\Enum;

abstract class ErrorEnum
{
    public const ERROR_PASSWORD = 'password_invalid';
    public const ERROR_PASSWORD_NOT_MATCH = 'password_not_match';

    public const CONSTRAINT_LENGTH = 'constraint_length';
    public const CONSTRAINT_LENGTH_EXACT = 'constraint_length_exact';
    public const CONSTRAINT_NOT_NULL = 'constraint_not_null';
    public const CONSTRAINT_NOT_BLANK = 'constraint_not_blank';
    public const CONSTRAINT_INVALID_EMAIL = 'constraint_invalid_email';
    public const CONSTRAINT_REGEX = 'constraint_regex';
    public const CONSTRAINT_UNIQUE = 'constraint_unique';
    public const CONSTRAINT_RANGE_MIN = 'constraint_range_min';
    public const CONSTRAINT_RANGE_MAX = 'constraint_range_max';
    public const CONSTRAINT_NOT_IN_RANGE = 'constraint_not_in_range';
    public const CONSTRAINT_INVALID_PHONE = 'constraint_invalid_phone';

    public const CODE_INVALID = 'code_invalid';

    public const FORM_KEY_INVALID = 'form_key_invalid';
    public const REQUEST_PASSWORD_ALREADY_EXIST = 'request_password_already_exist';
    public const INVALID_CHOICE = 'invalid_choice';

    public const SYMPTOMS_INVALID = 'symptoms_invalid';

    public const MEDIA_TYPE_INVALID = 'media_type_invalid';

    public const DATE_INVALID = 'date_invalid';

    public const SURVEY_ALREADY_DONE = 'survey_already_done';
    public const SURVEY_NOT_COMPLETED = 'survey_not_completed';
    public const SURVEY_JSON_INVALID = 'survey_json_invalid';

    public const RESOURCE_INVALID = 'resource_invalid';
    public const RESOURCE_CATEGORY_INVALID = 'resource_category_invalid';
    public const RECORDING_MAX_DAILY = 'recording_max_daily';
}
