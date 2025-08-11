import React, { useState } from 'react'
import { useForm } from 'react-hook-form'
import { yupResolver } from '@hookform/resolvers/yup'
import { Link } from 'react-router-dom'
import { useAuth } from '@/contexts/AuthContext'
import { validationSchemas } from '@/utils/validation'
import { ROUTES } from '@/utils/constants'
import Input from '@/components/common/Input'
import Button from '@/components/common/Button'

const LoginForm = ({ onSuccess }) => {
    const [showPassword, setShowPassword] = useState(false)
    const { login, loading } = useAuth()

    const {
        register,
        handleSubmit,
        formState: { errors },
        setError,
    } = useForm({
        resolver: yupResolver(validationSchemas.login),
        defaultValues: {
            email: '',
            password: '',
        },
    })

    const onSubmit = async (data) => {
        const result = await login(data)

        if (result.success) {
            if (onSuccess) {
                onSuccess()
            }
        } else {
            // Handle validation errors
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    setError(key, {
                        type: 'manual',
                        message: Array.isArray(result.errors[key])
                            ? result.errors[key][0]
                            : result.errors[key]
                    })
                })
            }
        }
    }

    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword)
    }

    return (
        <div className="w-full max-w-md mx-auto">
            <div className="bg-white dark:bg-gray-800 shadow-lg rounded-lg px-8 py-6">
                <div className="mb-6 text-center">
                    <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
                        Sign In
                    </h2>
                    <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Welcome back! Please sign in to your account.
                    </p>
                </div>

                <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                    <Input
                        {...register('email')}
                        type="email"
                        label="Email Address"
                        placeholder="Enter your email"
                        error={errors.email?.message}
                        autoComplete="email"
                    />

                    <Input
                        {...register('password')}
                        type={showPassword ? 'text' : 'password'}
                        label="Password"
                        placeholder="Enter your password"
                        error={errors.password?.message}
                        showPasswordToggle
                        showPassword={showPassword}
                        onTogglePassword={togglePasswordVisibility}
                        autoComplete="current-password"
                    />

                    <div className="flex items-center justify-between">
                        <div className="flex items-center">
                            <input
                                id="remember-me"
                                name="remember-me"
                                type="checkbox"
                                className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                            />
                            <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Remember me
                            </label>
                        </div>

                        <Link
                            to={ROUTES.FORGOT_PASSWORD}
                            className="text-sm text-primary-600 hover:text-primary-500"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <Button
                        type="submit"
                        className="w-full"
                        loading={loading}
                        disabled={loading}
                    >
                        Sign In
                    </Button>
                </form>

                <div className="mt-6 text-center">
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account?{' '}
                        <Link
                            to={ROUTES.REGISTER}
                            className="font-medium text-primary-600 hover:text-primary-500"
                        >
                            Sign up
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    )
}

export default LoginForm