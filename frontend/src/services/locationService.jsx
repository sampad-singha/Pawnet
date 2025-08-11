import api from './api'
import { API_ENDPOINTS } from '@/utils/constants'

export const locationService = {
    // Get all countries
    async getCountries() {
        const response = await api.get(API_ENDPOINTS.COUNTRIES)
        return response.data
    },

    // Get states by country
    async getStates(countryId) {
        const response = await api.get(API_ENDPOINTS.STATES(countryId))
        return response.data
    },

    // Get cities by country
    async getCities(countryId) {
        const response = await api.get(API_ENDPOINTS.CITIES(countryId))
        return response.data
    },

    // Get cities by state
    async getCitiesByState(stateId) {
        const response = await api.get(API_ENDPOINTS.CITIES_BY_STATE(stateId))
        return response.data
    },

    // Get phone codes
    async getPhoneCodes() {
        const response = await api.get(API_ENDPOINTS.PHONE_CODES)
        return response.data
    },

    // Get regions
    async getRegions() {
        const response = await api.get(API_ENDPOINTS.REGIONS)
        return response.data
    },

    // Get subregions
    async getSubregions(regionId) {
        const response = await api.get(API_ENDPOINTS.SUBREGIONS(regionId))
        return response.data
    }
}