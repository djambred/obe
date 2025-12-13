<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'code',
        'name',
        'vision',
        'mission',
        'objectives',
        'description',
        'logo',
        'dean_name',
        'phone',
        'email',
        'accreditation',
        'accreditation_date',
        'is_active',
    ];

    protected $casts = [
        'accreditation_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Mutators untuk handle repeater simple format
    public function setMissionAttribute($value)
    {
        if (is_array($value)) {
            // Extract 'item' dari repeater simple format
            $this->attributes['mission'] = json_encode(array_values(array_filter(array_map(function($item) {
                return is_array($item) && isset($item['item']) ? $item['item'] : $item;
            }, $value))));
        } else {
            $this->attributes['mission'] = $value;
        }
    }

    public function getMissionAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return [];
        }

        // Jika sudah dalam format repeater simple (array of arrays dengan key 'item')
        if (!empty($decoded) && is_array($decoded[0]) && isset($decoded[0]['item'])) {
            return $decoded;
        }

        // Convert array string ke format repeater simple
        return array_values(array_map(function($item) {
            return is_string($item) ? $item : $item;
        }, $decoded));
    }    public function setObjectivesAttribute($value)
    {
        if (is_array($value)) {
            // Extract 'item' dari repeater simple format
            $this->attributes['objectives'] = json_encode(array_values(array_filter(array_map(function($item) {
                return is_array($item) && isset($item['item']) ? $item['item'] : $item;
            }, $value))));
        } else {
            $this->attributes['objectives'] = $value;
        }
    }

    public function getObjectivesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return [];
        }

        // Jika sudah dalam format repeater simple (array of arrays dengan key 'item')
        if (!empty($decoded) && is_array($decoded[0]) && isset($decoded[0]['item'])) {
            return $decoded;
        }

        // Convert array string ke format repeater simple
        return array_values(array_map(function($item) {
            return is_string($item) ? $item : $item;
        }, $decoded));
    }    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class);
    }
}
