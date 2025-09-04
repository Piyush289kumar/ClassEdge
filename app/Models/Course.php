<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Course extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'name', 'description', 'credits', 'meta'];
    protected $casts = ['meta' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withPivot(['semester'])->withTimestamps();
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
