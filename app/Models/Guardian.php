<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class Guardian extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'relation',
        'email',
        'phone',
        'occupation',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'zip',
        'meta',
    ];

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

    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->withPivot(['relation_type', 'is_primary'])
            ->withTimestamps();
    }
}