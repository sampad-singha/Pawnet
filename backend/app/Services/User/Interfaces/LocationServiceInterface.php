<?php

namespace App\Services\User\Interfaces;

use App\Models\Util\City;
use App\Models\Util\Country;
use App\Models\Util\Region;
use App\Models\Util\State;
use App\Models\Util\Subregion;
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

    /**
     * Get all countries.
     *
     * @return Collection
     */
    public function getAllCountries(): Collection;

    /**
     * Get a region by its ID.
     *
     * @param int $regionId
     * @return Region
     */
    public function getRegionById(int $regionId): Region;

    /**
     * Get a subregion by its ID.
     *
     * @param int $subregionId
     * @return Subregion
     */
    public function getSubregionById(int $subregionId): Subregion;

    /**
     * Get a country by its ID.
     *
     * @param int $countryId
     * @return Country
     */
    public function getCountryById(int $countryId): Country;

    /**
     * Get a state by its ID.
     *
     * @param int $stateId
     * @return State
     */
    public function getStateById(int $stateId): State;

    /**
     * Get a city by its ID.
     *
     * @param int $cityId
     * @return City
     */
    public function getCityById(int $cityId): City;

    /**
     * Get phone codes.
     *
     */
    public function getPhoneCodes();
}
