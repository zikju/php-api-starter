<?php
declare(strict_types=1);

namespace zikju\Shared\Validation;

use Respect\Validation\Validator as v;

class BaseValidator
{

    /**
     * Validate 'id'
     * @param $id
     * @return bool
     */
    public static function id($id): bool
    {
        return v::intVal()
                ->min(1)
                ->validate($id);
    }

    /**
     * Validate 'text' input
     *
     * @param $text
     * @return bool
     */
    public static function text($text): bool
    {
        return v::stringType()
            ->notEmpty()
            ->length(1, 10000)
            ->validate($text);
    }
}