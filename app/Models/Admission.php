<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Admission extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'course_id',
        'batch_id',
        'admitted_on',
        'status',
        'fee_total',
        'fee_paid',
        'payment_reference',
        'meta',
    ];

    protected $casts = [
        'admitted_on' => 'date',
        'fee_total' => 'decimal:2',
        'fee_paid' => 'decimal:2',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
