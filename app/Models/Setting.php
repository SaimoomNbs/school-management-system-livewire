<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key_name',
        'value',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key_name', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key_name' => $key],
            ['value' => $value]
        );
    }
}
