<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'course_duration',
        'batch_id',
        'guardian_id',
        'admitted_on',
        'status',
        'fee_total',
        'fee_paid',
        'payment_reference',
        'meta',
        'email',
        'first_name',
        'last_name',
        'address',
        'mobile_number',
        'dob',
        'gender',
        'payment_method',
        'heard_about',
        'store_id',
        'occupation',
        'class_days',
        'batch_time',
        'fee_submitted',
        'photo_path',
        // Add other fields as needed
    ];

    protected $casts = [
        'admitted_on' => 'date',
        'fee_total' => 'decimal:2',
        'fee_paid' => 'decimal:2',
        'meta' => 'array',
        'class_days' => 'array',
        'batch_time' => 'datetime:H:i',
        'heard_about' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
        static::created(function ($admission) {
            if (empty($admission->student_id)) {
                $student = Student::firstOrCreate(
                    ['email' => $admission->email],
                    [
                        'first_name' => $admission->first_name,
                        'last_name' => $admission->last_name,
                        'address_line1' => $admission->address,
                        'phone' => $admission->mobile_number,
                        'dob' => $admission->dob,
                        'gender' => $admission->gender,
                        'photo_path' => $admission->photo_path,
                    ]
                );

                $admission->student_id = $student->id;
                $admission->save();
            } else {
                $student = Student::find($admission->student_id);
            }

            if ($student && !$student->guardians()->exists() && !empty($admission->guardian_name)) {
                $guardian = Guardian::create([
                    'first_name' => $admission->guardian_name,
                    'last_name' => $admission->last_name,
                    'email' => $admission->email,
                    'phone' => $admission->guardian_phone,
                    'occupation' => 'Other',
                    'address_line1' => $admission->address,
                    'meta' => [],
                ]);

                $student->guardians()->attach($guardian->id, [
                    'relation_type' => 'guardian',
                    'is_primary' => true,
                ]);
            }
        });

    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
