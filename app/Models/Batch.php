<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Batch extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'course_id',
        'classroom_id',
        'code',
        'name',
        'starts_at',
        'ends_at',
        'capacity',
        'status',
        'meta',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withPivot(['periods_per_week'])->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot(['roll_no', 'joined_on'])->withTimestamps();
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
