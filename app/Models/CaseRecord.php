<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseRecord extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'patient_name',
        'patient_phone',
        'patient_age',
        'patient_gender',
        'doctor_name',
        'notes',
        'status',
        'total_price',
        'created_by',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function caseTests()
    {
        return $this->hasMany(CaseTest::class, 'case_id');
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'case_tests', 'case_id', 'test_id')
            ->withPivot(['price', 'result', 'status', 'id']);
    }

    public static function generateCaseNumber(): string
    {
        $date = now()->format('Ymd');
        $last = self::where('case_number', 'LIKE', "CASE-{$date}-%")
            ->orderByDesc('id')
            ->first();

        if ($last) {
            $num = intval(substr($last->case_number, -4)) + 1;
        } else {
            $num = 1;
        }

        return "CASE-" . $date . "-" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            default => 'secondary',
        };
    }
}
