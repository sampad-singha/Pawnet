<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $table = "states";

    protected $fillable = [
        'name',
        'country_id',
        'country_code',
        'iso2',
        'type',
        'level',
        'latitude',
        'longitude',
        'flag',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'flag' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
