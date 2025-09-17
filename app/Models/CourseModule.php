<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class CourseModule extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'resources',
    ];

    protected $casts = [
        'resources' => 'array',
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
}
