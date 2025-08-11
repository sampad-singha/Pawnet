import React, { useEffect } from 'react'
import { useNavigate, useLocation } from 'react-router-dom'
import { useAuth } from '@/contexts/AuthContext'
import { ROUTES } from '@/utils/constants'
import LoginForm from '@/components/auth/LoginForm'
import ThemeToggle from '@/components/common/ThemeToggle'

const Login = () => {
    const navigate = useNavigate()
    const location = useLocation()
    const { isAuthenticated } = useAuth()

    // Redirect if already authenticated
    useEffect(() => {
        if (isAuthenticated) {
            const from = location.state?.from?.pathname || ROUTES.DASHBOARD
            navigate(from, { replace: true })
        }
    }, [isAuthenticated, navigate, location.state])

    const handleLoginSuccess = () => {
        const from = location.state?.from?.pathname || ROUTES.DASHBOARD
        navigate(from, { replace: true })
    }

    if (isAuthenticated) {
        return null // Prevent flash of login form
    }

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div className="absolute top-4 right-4">
                <ThemeToggle />
            </div>

            <div className="max-w-md w-full space-y-8">
                <div className="text-center">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Welcome Back
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Sign in to access your account
                    </p>
                </div>

                <LoginForm onSuccess={handleLoginSuccess} />
            </div>
        </div>
    )
}

export default Login