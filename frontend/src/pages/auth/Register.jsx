import React, { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '@/contexts/AuthContext'
import { ROUTES } from '@/utils/constants'
import RegisterForm from '@/components/auth/RegisterForm'
import ThemeToggle from '@/components/common/ThemeToggle'

const Register = () => {
    const navigate = useNavigate()
    const { isAuthenticated } = useAuth()

    // Redirect if already authenticated
    useEffect(() => {
        if (isAuthenticated) {
            navigate(ROUTES.DASHBOARD, { replace: true })
        }
    }, [isAuthenticated, navigate])

    const handleRegisterSuccess = () => {
        navigate(ROUTES.DASHBOARD, { replace: true })
    }

    if (isAuthenticated) {
        return null // Prevent flash of register form
    }

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div className="absolute top-4 right-4">
                <ThemeToggle />
            </div>

            <div className="max-w-md w-full space-y-8">
                <div className="text-center">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Join Us Today
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Create your account to get started
                    </p>
                </div>

                <RegisterForm onSuccess={handleRegisterSuccess} />
            </div>
        </div>
    )
}

export default Register