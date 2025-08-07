<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Services\User\Interfaces\LocationServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    protected LocationServiceInterface $locationService;

    public function __construct(LocationServiceInterface $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Get all regions.
     *
     * @return JsonResponse
     */
    public function getRegions()
    {
        try {
            $regions = $this->locationService->getRegions();
            return response()->json($regions);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching regions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subregions by region ID.
     *
     * @param int $regionId
     * @return JsonResponse
     */
    public function getSubregions(int $regionId)
    {
        try {
            $subregions = $this->locationService->getSubregions($regionId);
            return response()->json($subregions);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching subregions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get countries by subregion ID.
     *
     * @param int $subregionId
     * @return JsonResponse
     */
    public function getCountries(int $subregionId)
    {
        try {
            $countries = $this->locationService->getCountries($subregionId);
            return response()->json($countries);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching countries.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get states by country ID.
     *
     * @param int $countryId
     * @return JsonResponse
     */
    public function getStates(int $countryId)
    {
        try {
            $states = $this->locationService->getStates($countryId);
            return response()->json($states);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching states.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by state ID.
     *
     * @param int $stateId
     * @return JsonResponse
     */
    public function getCities(int $stateId)
    {
        try {
            $cities = $this->locationService->getCities($stateId);
            return response()->json($cities);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching cities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by country ID.
     *
     * @param int $countryId
     * @return JsonResponse
     */
    public function getCitiesByCountry(int $countryId)
    {
        try {
            $cities = $this->locationService->getCitiesByCountry($countryId);
            return response()->json($cities);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching cities by country.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all countries.
     *
     * @return JsonResponse
     */
    public function getAllCountries()
    {
        try {
            $countries = $this->locationService->getAllCountries();
            return response()->json($countries);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching all countries.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a region by its ID.
     *
     * @param int $regionId
     * @return JsonResponse
     */
    public function getRegionById(int $regionId)
    {
        try {
            $region = $this->locationService->getRegionById($regionId);
            return response()->json($region);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching the region.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a subregion by its ID.
     *
     * @param int $subregionId
     * @return JsonResponse
     */
    public function getSubregionById(int $subregionId)
    {
        try {
            $subregion = $this->locationService->getSubregionById($subregionId);
            return response()->json($subregion);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching the subregion.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a country by its ID.
     *
     * @param int $countryId
     * @return JsonResponse
     */
    public function getCountryById(int $countryId)
    {
        try {
            $country = $this->locationService->getCountryById($countryId);
            return response()->json($country);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching the country.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a state by its ID.
     *
     * @param int $stateId
     * @return JsonResponse
     */
    public function getStateById(int $stateId)
    {
        try {
            $state = $this->locationService->getStateById($stateId);
            return response()->json($state);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching the state.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a city by its ID.
     *
     * @param int $cityId
     * @return JsonResponse
     */
    public function getCityById(int $cityId)
    {
        try {
            $city = $this->locationService->getCityById($cityId);
            return response()->json($city);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching the city.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPhoneCodes()
    {
        try {
            $phoneCodes = $this->locationService->getPhoneCodes();
            return response()->json($phoneCodes);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while fetching phone codes.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
