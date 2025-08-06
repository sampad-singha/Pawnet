<?php

namespace App\Services\User\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface LocationServiceInterface
{
    /**
     * Get all regions.
     *
     * @return Collection
     */
    public function getRegions(): Collection;

    /**
     * Get subregions by region ID.
     *
     * @param int $regionId
     * @return Collection
     */
    public function getSubregions(int $regionId): Collection;

    /**
     * Get countries by subregion ID.
     *
     * @param int $subregionId
     * @return Collection
     */
    public function getCountries(int $subregionId): Collection;

    /**
     * Get states by country ID.
     *
     * @param int $countryId
     * @return Collection
     */
    public function getStates(int $countryId): Collection;

    /**
     * Get cities by state ID.
     *
     * @param int $stateId
     * @return Collection
     */
    public function getCities(int $stateId): Collection;

    /**
     * Get cities by country ID.
     *
     * @param int $countryId
     * @return Collection
     */
    public function getCitiesByCountry(int $countryId): Collection;
}
