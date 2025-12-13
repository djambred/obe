<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'code',
        'name',
        'level',
        'vision',
        'mission',
        'objectives',
        'description',
        'head_of_program',
        'secretary',
        'accreditation',
        'accreditation_date',
        'accreditation_number',
        'standard_study_period',
        'degree_awarded',
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
    }    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyFields(): HasMany
    {
        return $this->hasMany(StudyField::class);
    }

    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function graduateProfiles(): HasMany
    {
        return $this->hasMany(GraduateProfile::class);
    }

    public function programLearningOutcomes(): HasMany
    {
        return $this->hasMany(ProgramLearningOutcome::class);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(Lecturer::class);
    }

    public function continuousImprovements(): HasMany
    {
        return $this->hasMany(ContinuousImprovement::class);
    }
}
