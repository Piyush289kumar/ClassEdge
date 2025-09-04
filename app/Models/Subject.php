<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Subject extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code','name','credits','description','meta'];
    protected $casts = ['meta' => 'array'];

    public function courses() {
        return $this->belongsToMany(Course::class)->withPivot(['semester'])->withTimestamps();
    }

    public function batches() {
        return $this->belongsToMany(Batch::class)->withPivot(['periods_per_week'])->withTimestamps();
    }
}
