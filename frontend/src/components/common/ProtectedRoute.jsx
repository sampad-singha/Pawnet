import React from 'react'
import { Navigate, useLocation } from 'react-router-dom'
import { useAuth } from '@/contexts/AuthContext'
import { ROUTES } from '@/utils/constants'
import Loading from './Loading'

const ProtectedRoute = ({ children }) => {
    const { isAuthenticated, loading } = useAuth()
    const location = useLocation()

    if (loading) {
        return <Loading fullScreen text="Checking authentication..." />
    }

    if (!isAuthenticated) {
        // Redirect to login page with return url
        return <Navigate to={ROUTES.LOGIN} state={{ from: location }} replace />
    }

    return children
}

export default ProtectedRoute