import api, { getCsrfCookie } from './api'
import { API_ENDPOINTS } from '@/utils/constants'

export const authService = {
    // Login user
    async login(credentials) {
        await getCsrfCookie() // Get CSRF cookie for Laravel Sanctum
        const response = await api.post(API_ENDPOINTS.LOGIN, credentials)
        return response.data
    },

    // Register user
    async register(userData) {
        await getCsrfCookie()
        const response = await api.post(API_ENDPOINTS.REGISTER, userData)
        return response.data
    },

    // Logout user
    async logout() {
        const response = await api.post(API_ENDPOINTS.LOGOUT)
        return response.data
    },

    // Logout from all devices
    async logoutAll() {
        const response = await api.post(API_ENDPOINTS.LOGOUT_ALL)
        return response.data
    },

    // Refresh access token
    async refreshToken(refreshToken) {
        const response = await api.post(API_ENDPOINTS.REFRESH_TOKEN, {
            refresh_token: refreshToken
        })
        return response.data
    },

    // Get current user
    async getUser() {
        const response = await api.get(API_ENDPOINTS.USER)
        return response.data
    },

    // Verify token
    async verifyToken() {
        const response = await api.get(API_ENDPOINTS.VERIFY_TOKEN)
        return response.data
    },

    // Forgot password
    async forgotPassword(email) {
        await getCsrfCookie()
        const response = await api.post(API_ENDPOINTS.FORGOT_PASSWORD, { email })
        return response.data
    },

    // Reset password
    async resetPassword(data) {
        await getCsrfCookie()
        const response = await api.post(API_ENDPOINTS.RESET_PASSWORD, data)
        return response.data
    },

    // Change password (when logged in)
    async changePassword(data) {
        const response = await api.post(API_ENDPOINTS.CHANGE_PASSWORD, data)
        return response.data
    },

    // Set password (for users who registered via social login)
    async setPassword(data) {
        const response = await api.post(API_ENDPOINTS.SET_PASSWORD, data)
        return response.data
    }
}