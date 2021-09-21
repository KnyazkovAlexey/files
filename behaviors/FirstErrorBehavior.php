<?php

namespace app\behaviors;

use yii\base\Behavior;

/**
 * Поведение для получения первой ошибки валидации из ActiveRecord.
 */
class FirstErrorBehavior extends Behavior
{
    /**
     * Получение текста первой ошибки валидации.
     *
     * @return string|null
     */
    public function getFirstErrorMessage(): ?string
    {
        $errors = $this->owner->getFirstErrors();

        return reset($errors);
    }
}