<?php

namespace App\Services\User;

use App\Models\Util\City;
use App\Models\Util\Country;
use App\Models\Util\Region;
use App\Models\Util\State;
use App\Models\Util\Subregion;
use App\Services\User\Interfaces\LocationServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class LocationService implements LocationServiceInterface
{
    /**
     * Get all regions.
     *
     * @return Collection
     */
    public function getRegions(): Collection
    {
        return Region::all();
    }

    /**
     * @throws Exception
     */
    public function getSubregions(int $regionId): Collection
    {
        $region = Region::find($regionId);
        if (!$region) {
            throw new Exception("Region not found");
        }
        return $region->subregions;
    }

    /**
     * @throws Exception
     */
    public function getCountries(int $subregionId): Collection
    {
        $subregion = Subregion::find($subregionId);
        if (!$subregion) {
            throw new Exception("Subregion not found");
        }
        return $subregion->countries;
    }

    /**
     * @throws Exception
     */
    public function getStates(int $countryId): Collection
    {
        $country = Country::find($countryId);
        if (!$country) {
            throw new Exception("Country not found");
        }
        return $country->states;
    }

    /**
     * @throws Exception
     */
    public  function getCities(int $stateId): Collection
    {
        $state = State::find($stateId);
        if (!$state) {
            throw new Exception("State not found");
        }
        return $state->cities;
    }

    /**
     * @throws Exception
     */
    public function getCitiesByCountry(int $countryId): Collection
    {
        $country = Country::find($countryId);
        if (!$country) {
            throw new Exception("Country not found");
        }
        return $country->cities;
    }

    //get all countries
    public function getAllCountries(): Collection
    {
        return Country::all();
    }

    //get each by id

    /**
     * @throws Exception
     */
    public function getRegionById(int $regionId): Region
    {
        $region =  Region::find($regionId);
        if (!$region) {
            throw new Exception("Region not found");
        }
        return $region;
    }

    /**
     * @throws Exception
     */
    public function getSubregionById(int $subregionId): Subregion
    {
        $subregion = Subregion::find($subregionId);
        if (!$subregion) {
            throw new Exception("Subregion not found");
        }
        return $subregion;
    }

    /**
     * @throws Exception
     */
    public function getCountryById(int $countryId): Country
    {
        $country = Country::find($countryId);
        if (!$country) {
            throw new Exception("Country not found");
        }
        return $country;
    }

    /**
     * @throws Exception
     */
    public function getStateById(int $stateId): State
    {
        $state = State::find($stateId);
        if (!$state) {
            throw new Exception("State not found");
        }
        return $state;
    }

    /**
     * @throws Exception
     */
    public function getCityById(int $cityId): City
    {
        $city = City::find($cityId);
        if (!$city) {
            throw new Exception("City not found");
        }
        return $city;
    }
}
