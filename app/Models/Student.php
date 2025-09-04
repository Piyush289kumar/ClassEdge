<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'roll_no',
        'registration_no',
        'dob',
        'gender',
        'email',
        'phone',
        'alt_phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'zip',
        'photo_path',
        'meta',
    ];

    protected $casts = [
        'dob' => 'date',
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

    // Relations
    public function guardians()
    {
        return $this->belongsToMany(Guardian::class)
            ->withPivot(['relation_type', 'is_primary'])
            ->withTimestamps();
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class)
            ->withPivot(['roll_no', 'joined_on'])
            ->withTimestamps();
    }
}
