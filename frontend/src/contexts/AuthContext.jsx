import React, { createContext, useContext, useEffect, useReducer } from 'react'
import { STORAGE_KEYS } from '@/utils/constants'
import { authService } from '@/services/authService'
import toast from 'react-hot-toast'

// Auth reducer
const authReducer = (state, action) => {
    switch (action.type) {
        case 'SET_LOADING':
            return {
                ...state,
                loading: action.payload
            }
        case 'LOGIN_SUCCESS':
            return {
                ...state,
                user: action.payload.user,
                isAuthenticated: true,
                loading: false
            }
        case 'LOGOUT':
            return {
                ...state,
                user: null,
                isAuthenticated: false,
                loading: false
            }
        case 'UPDATE_USER':
            return {
                ...state,
                user: { ...state.user, ...action.payload }
            }
        case 'SET_ERROR':
            return {
                ...state,
                error: action.payload,
                loading: false
            }
        case 'CLEAR_ERROR':
            return {
                ...state,
                error: null
            }
        default:
            return state
    }
}

// Initial state
const initialState = {
    user: null,
    isAuthenticated: false,
    loading: true,
    error: null
}

// Create context
const AuthContext = createContext({
    ...initialState,
    login: () => {},
    register: () => {},
    logout: () => {},
    updateUser: () => {},
    clearError: () => {},
})

export const useAuth = () => {
    const context = useContext(AuthContext)
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider')
    }
    return context
}

export const AuthProvider = ({ children }) => {
    const [state, dispatch] = useReducer(authReducer, initialState)

    // Check if user is authenticated on app load
    useEffect(() => {
        const initializeAuth = async () => {
            dispatch({ type: 'SET_LOADING', payload: true })

            try {
                const token = localStorage.getItem(STORAGE_KEYS.ACCESS_TOKEN)
                const savedUser = localStorage.getItem(STORAGE_KEYS.USER)

                if (token && savedUser) {
                    // Verify token is still valid
                    const user = await authService.verifyToken()
                    dispatch({
                        type: 'LOGIN_SUCCESS',
                        payload: { user }
                    })
                } else {
                    dispatch({ type: 'SET_LOADING', payload: false })
                }
            } catch (error) {
                // Token is invalid, clear storage
                localStorage.removeItem(STORAGE_KEYS.ACCESS_TOKEN)
                localStorage.removeItem(STORAGE_KEYS.REFRESH_TOKEN)
                localStorage.removeItem(STORAGE_KEYS.USER)
                dispatch({ type: 'SET_LOADING', payload: false })
            }
        }

        initializeAuth()
    }, [])

    const login = async (credentials) => {
        dispatch({ type: 'SET_LOADING', payload: true })
        dispatch({ type: 'CLEAR_ERROR' })

        try {
            const { user, access_token, refresh_token } = await authService.login(credentials)

            // Store tokens and user data
            localStorage.setItem(STORAGE_KEYS.ACCESS_TOKEN, access_token)
            if (refresh_token) {
                localStorage.setItem(STORAGE_KEYS.REFRESH_TOKEN, refresh_token)
            }
            localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(user))

            dispatch({
                type: 'LOGIN_SUCCESS',
                payload: { user }
            })

            toast.success('Login successful!')
            return { success: true }
        } catch (error) {
            const errorMessage = error.response?.data?.message || 'Login failed'
            dispatch({ type: 'SET_ERROR', payload: errorMessage })
            toast.error(errorMessage)
            return { success: false, error: errorMessage }
        }
    }

    const register = async (userData) => {
        dispatch({ type: 'SET_LOADING', payload: true })
        dispatch({ type: 'CLEAR_ERROR' })

        try {
            const { user, access_token, refresh_token } = await authService.register(userData)

            // Store tokens and user data
            localStorage.setItem(STORAGE_KEYS.ACCESS_TOKEN, access_token)
            if (refresh_token) {
                localStorage.setItem(STORAGE_KEYS.REFRESH_TOKEN, refresh_token)
            }
            localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(user))

            dispatch({
                type: 'LOGIN_SUCCESS',
                payload: { user }
            })

            toast.success('Registration successful!')
            return { success: true }
        } catch (error) {
            const errorMessage = error.response?.data?.message || 'Registration failed'
            dispatch({ type: 'SET_ERROR', payload: errorMessage })
            toast.error(errorMessage)
            return { success: false, error: errorMessage, errors: error.response?.data?.errors }
        }
    }

    const logout = async (logoutAll = false) => {
        dispatch({ type: 'SET_LOADING', payload: true })

        try {
            if (logoutAll) {
                await authService.logoutAll()
            } else {
                await authService.logout()
            }
        } catch (error) {
            // Even if logout fails on server, clear local data
            console.error('Logout error:', error)
        } finally {
            // Clear local storage
            localStorage.removeItem(STORAGE_KEYS.ACCESS_TOKEN)
            localStorage.removeItem(STORAGE_KEYS.REFRESH_TOKEN)
            localStorage.removeItem(STORAGE_KEYS.USER)

            dispatch({ type: 'LOGOUT' })
            toast.success('Logged out successfully')
        }
    }

    const updateUser = (userData) => {
        dispatch({ type: 'UPDATE_USER', payload: userData })

        // Update localStorage
        const currentUser = JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || '{}')
        const updatedUser = { ...currentUser, ...userData }
        localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(updatedUser))
    }

    const clearError = () => {
        dispatch({ type: 'CLEAR_ERROR' })
    }

    const value = {
        ...state,
        login,
        register,
        logout,
        updateUser,
        clearError,
    }

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    )
}