<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $table = 'regions';

    protected $fillable = [
        'name',
        'translations',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'translations' => 'array',
        'flag' => 'boolean',
    ];

    public function countries(): hasMany
    {
        return $this->hasMany(Country::class, 'region_id');
    }

    public function subregions(): hasMany
    {
        return $this->hasMany(Subregion::class, 'region_id');
    }
}
