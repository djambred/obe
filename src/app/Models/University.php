<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'vision',
        'mission',
        'objectives',
        'description',
        'logo',
        'rector_name',
        'address',
        'phone',
        'email',
        'website',
        'accreditation',
        'accreditation_date',
        'is_active',
    ];

    protected $casts = [
        'mission' => 'array',
        'objectives' => 'array',
        'accreditation_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }
}
