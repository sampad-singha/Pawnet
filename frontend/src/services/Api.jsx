import axios from 'axios'
import { API_BASE_URL, API_ENDPOINTS, STORAGE_KEYS } from '@/utils/constants'
import toast from 'react-hot-toast'

// Create axios instance
const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true, // Important for Laravel Sanctum
})

// Request interceptor to add auth token
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem(STORAGE_KEYS.ACCESS_TOKEN)
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Response interceptor for handling auth errors and token refresh
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config

        // Handle 401 unauthorized errors
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true

            try {
                const refreshToken = localStorage.getItem(STORAGE_KEYS.REFRESH_TOKEN)
                if (!refreshToken) {
                    throw new Error('No refresh token')
                }

                // Try to refresh token
                const response = await axios.post(`${API_BASE_URL}${API_ENDPOINTS.REFRESH_TOKEN}`, {
                    refresh_token: refreshToken
                })

                const { access_token, refresh_token: newRefreshToken } = response.data

                // Update stored tokens
                localStorage.setItem(STORAGE_KEYS.ACCESS_TOKEN, access_token)
                if (newRefreshToken) {
                    localStorage.setItem(STORAGE_KEYS.REFRESH_TOKEN, newRefreshToken)
                }

                // Update original request with new token
                originalRequest.headers.Authorization = `Bearer ${access_token}`

                return api(originalRequest)
            } catch (refreshError) {
                // Refresh failed, redirect to login
                localStorage.removeItem(STORAGE_KEYS.ACCESS_TOKEN)
                localStorage.removeItem(STORAGE_KEYS.REFRESH_TOKEN)
                localStorage.removeItem(STORAGE_KEYS.USER)

                // Redirect to login page
                if (window.location.pathname !== '/login') {
                    window.location.href = '/login'
                }

                return Promise.reject(refreshError)
            }
        }

        // Handle other errors
        if (error.response?.status === 403) {
            toast.error('Access denied. You don\'t have permission to perform this action.')
        } else if (error.response?.status === 404) {
            toast.error('Resource not found.')
        } else if (error.response?.status === 422) {
            // Validation errors - these are handled by individual components
            return Promise.reject(error)
        } else if (error.response?.status >= 500) {
            toast.error('Server error. Please try again later.')
        } else if (!error.response) {
            toast.error('Network error. Please check your connection.')
        }

        return Promise.reject(error)
    }
)

// Helper function to get CSRF cookie (for Laravel Sanctum)
export const getCsrfCookie = async () => {
    try {
        await api.get('/sanctum/csrf-cookie')
    } catch (error) {
        console.error('Failed to get CSRF cookie:', error)
    }
}

export default api