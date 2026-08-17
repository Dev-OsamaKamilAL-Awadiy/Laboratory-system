<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseTest extends Model
{
    protected $table = 'case_tests';

    protected $fillable = [
        'case_id',
        'test_id',
        'price',
        'result',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function case()
    {
        return $this->belongsTo(CaseRecord::class, 'case_id');
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function getTestNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->test->name_ar : $this->test->name_en;
    }
}
