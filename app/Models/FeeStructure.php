<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FeeStructure extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'course_id',
        'batch_id',
        'name',
        'amount',
        'due_date',
        'is_recurring',
        'frequency',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_recurring' => 'boolean',
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    // Relations
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }


    public function getCurrentDueDate(): ?Carbon
    {
        if (!$this->due_date) {
            return null;
        }

        $dueDate = Carbon::parse($this->due_date);

        // If recurring, shift due date forward until it matches current period
        switch ($this->frequency) {
            case 'monthly':
                while ($dueDate->lt(now()->startOfMonth())) {
                    $dueDate->addMonth();
                }
                break;
            case 'quarterly':
                while ($dueDate->lt(now()->firstOfQuarter())) {
                    $dueDate->addMonths(3);
                }
                break;
            case 'yearly':
                while ($dueDate->lt(now()->startOfYear())) {
                    $dueDate->addYear();
                }
                break;
        }

        return $dueDate;
    }
    
}
