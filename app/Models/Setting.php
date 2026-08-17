<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );
    }

    public static function getSystemName(): string
    {
        $lang = app()->getLocale();
        return $lang === 'ar'
            ? static::getValue('system_name_ar', 'نظام المختبر الطبي')
            : static::getValue('system_name_en', 'Medical Laboratory System');
    }
}
