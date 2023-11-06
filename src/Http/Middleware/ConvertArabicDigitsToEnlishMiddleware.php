<?php

namespace Alkoumi\LaravelArabicNumbers\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as Middleware;

class ConvertArabicDigitsToEnglishMiddleware extends Middleware
{
    protected const HINDI_NUMBERS = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    protected const ARABIC_NUMBERS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    /**
     * The attributes that should not be transformed.
     *
     * @var array
     */
    protected $except = [
        'password', 'password_confirmation', 'password_current',
    ];

    /**
     * Transform the given value.
     *
     * @param $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (!$value) {
            return $value;
        }

        if (in_array($key, $this->except, true)) {
            return $value;
        }

        if (!$this->containsHindiNumbers($value)) {
            return $value;
        }

        return $this->transNumber($value);
    }

    protected function containsHindiNumbers($value)
    {
        foreach (Self::HINDI_NUMBERS as $number) {
            if (strpos($value, $number) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function transNumber($value)
    {
        return str_replace(Self::HINDI_NUMBERS, Self::ARABIC_NUMBERS, $value);
    }
}
