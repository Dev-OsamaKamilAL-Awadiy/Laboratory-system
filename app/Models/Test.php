<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'price',
        'category',
        'description_ar',
        'description_en',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function caseTests()
    {
        return $this->hasMany(CaseTest::class);
    }

    public static function getCategories()
    {
        return self::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');
    }
}
