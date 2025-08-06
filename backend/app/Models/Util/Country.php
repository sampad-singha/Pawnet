<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{

    protected $table = 'countries';
    protected $fillable = [
        'name',
        'iso3',
        'numeric_code',
        'iso2',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'region_id',
        'subregion',
        'subregion_id',
        'nationality',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'timezones' => 'array',
        'translations' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'flag' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function subregion(): BelongsTo
    {
        return $this->belongsTo(Subregion::class, 'subregion_id');
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id');
    }

    public function cities(): hasMany
    {
        return $this->hasMany(City::class, State::class, 'country_id', 'state_id');
    }
}
