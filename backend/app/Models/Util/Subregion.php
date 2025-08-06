<?php

namespace App\Models\Util;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subregion extends Model
{
    protected $table = 'subregions';

    protected $fillable = [
        'name',
        'translations',
        'region_id',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'translations' => 'array',
        'flag' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function countries(): hasMany
    {
        return $this->hasMany(Country::class, 'subregion_id');
    }

}
