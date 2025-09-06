<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FeePayment extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'admission_id',
        'fee_structure_id',
        'amount',
        'paid_on',
        'status',
        'payment_mode',
        'reference_number',
        'late_fee',
        'discount',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_on' => 'date',
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });

        // 🔹 Auto-calculate late fee before saving
        static::saving(function ($model) {
            if ($model->feeStructure && $model->paid_on) {
                $dueDate = $model->feeStructure->getCurrentDueDate();
                $paidOn = Carbon::parse($model->paid_on);

                if ($dueDate && $paidOn->gt($dueDate)) {
                    $daysLate = $paidOn->diffInDays($dueDate);
                    $lateFeePerDay = $model->feeStructure->late_fee_per_day ?? 0;

                    $model->late_fee = $daysLate * $lateFeePerDay;
                } else {
                    $model->late_fee = 0;
                }
            }
        });

    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }
}
