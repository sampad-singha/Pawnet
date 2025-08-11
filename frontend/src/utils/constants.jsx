// API Configuration
export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
export const API_ENDPOINTS = {
    // Auth endpoints
    LOGIN: '/api/login',
    REGISTER: '/api/register',
    LOGOUT: '/api/logout',
    LOGOUT_ALL: '/api/logout-all',
    REFRESH_TOKEN: '/api/refresh-token',
    FORGOT_PASSWORD: '/api/forgot-password',
    RESET_PASSWORD: '/api/reset-password',
    CHANGE_PASSWORD: '/api/user/change-password',
    SET_PASSWORD: '/api/user/set-password',
    USER: '/api/user',
    VERIFY_TOKEN: '/api/user/verify-token',

    // Profile endpoints
    PROFILE_CREATE: '/api/users/profile/create',
    PROFILE_UPDATE: '/api/users/profile/update',
    PROFILE_SHOW: (profileId) => `/api/users/profile/${profileId}`,
    PROFILE_VISIBILITY: '/api/users/profile/visibility',
    PHONE_VERIFY: '/api/users/profile/phone-number/verify',
    PHONE_VERIFY_CODE: '/api/users/profile/phone-number/verify-code',

    // Friends endpoints
    FRIENDS: '/api/friends',
    SEND_FRIEND_REQUEST: (friendId) => `/api/friends/send-request/${friendId}`,
    ACCEPT_FRIEND_REQUEST: (friendId) => `/api/friends/accept-request/${friendId}`,
    REJECT_FRIEND_REQUEST: (friendId) => `/api/friends/reject-request/${friendId}`,
    CANCEL_FRIEND_REQUEST: (friendId) => `/api/friends/cancel-request/${friendId}`,
    UNFRIEND: (friendId) => `/api/friends/unfriend/${friendId}`,
    PENDING_REQUESTS: '/api/friends/request/pending',
    SENT_REQUESTS: '/api/friends/request/sent',

    // Location endpoints
    COUNTRIES: '/api/location/countries',
    STATES: (countryId) => `/api/location/countries/${countryId}/states`,
    CITIES: (countryId) => `/api/location/countries/${countryId}/cities`,
    CITIES_BY_STATE: (stateId) => `/api/location/states/${stateId}/cities`,
    PHONE_CODES: '/api/location/phone-codes',
    REGIONS: '/api/location/regions',
    SUBREGIONS: (regionId) => `/api/location/regions/${regionId}/subregions`,
}

// Storage keys
export const STORAGE_KEYS = {
    ACCESS_TOKEN: 'access_token',
    REFRESH_TOKEN: 'refresh_token',
    USER: 'user',
    THEME: 'theme',
}

// Theme constants
export const THEMES = {
    LIGHT: 'light',
    DARK: 'dark',
}

// App routes
export const ROUTES = {
    HOME: '/',
    LOGIN: '/login',
    REGISTER: '/register',
    DASHBOARD: '/dashboard',
    PROFILE: '/profile',
    FORGOT_PASSWORD: '/forgot-password',
    RESET_PASSWORD: '/reset-password',
}