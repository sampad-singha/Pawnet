import api from './api'
import { API_ENDPOINTS } from '@/utils/constants'

export const userService = {
    // Create user profile
    async createProfile(profileData) {
        const response = await api.post(API_ENDPOINTS.PROFILE_CREATE, profileData)
        return response.data
    },

    // Update user profile
    async updateProfile(profileData) {
        const response = await api.post(API_ENDPOINTS.PROFILE_UPDATE, profileData)
        return response.data
    },

    // Get user profile by ID
    async getProfile(profileId) {
        const response = await api.get(API_ENDPOINTS.PROFILE_SHOW(profileId))
        return response.data
    },

    // Change profile visibility
    async changeVisibility(visibility) {
        const response = await api.post(API_ENDPOINTS.PROFILE_VISIBILITY, { visibility })
        return response.data
    },

    // Send phone verification code
    async sendPhoneVerification(phoneNumber) {
        const response = await api.post(API_ENDPOINTS.PHONE_VERIFY, { phone_number: phoneNumber })
        return response.data
    },

    // Verify phone number with code
    async verifyPhone(phoneNumber, code) {
        const response = await api.post(API_ENDPOINTS.PHONE_VERIFY_CODE, {
            phone_number: phoneNumber,
            code
        })
        return response.data
    }
}