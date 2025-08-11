import React, { useState } from 'react'
import { useForm } from 'react-hook-form'
import { yupResolver } from '@hookform/resolvers/yup'
import { Link } from 'react-router-dom'
import { useAuth } from '@/contexts/AuthContext'
import { validationSchemas } from '@/utils/validation'
import { ROUTES } from '@/utils/constants'
import Input from '@/components/common/Input'
import Button from '@/components/common/Button'

const RegisterForm = ({ onSuccess }) => {
    const [showPassword, setShowPassword] = useState(false)
    const [showConfirmPassword, setShowConfirmPassword] = useState(false)
    const { register: registerUser, loading } = useAuth()

    const {
        register,
        handleSubmit,
        formState: { errors },
        setError,
    } = useForm({
        resolver: yupResolver(validationSchemas.register),
        defaultValues: {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        },
    })

    const onSubmit = async (data) => {
        const result = await registerUser(data)

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

    const toggleConfirmPasswordVisibility = () => {
        setShowConfirmPassword(!showConfirmPassword)
    }

    return (
        <div className="w-full max-w-md mx-auto">
            <div className="bg-white dark:bg-gray-800 shadow-lg rounded-lg px-8 py-6">
                <div className="mb-6 text-center">
                    <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
                        Create Account
                    </h2>
                    <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Join us today! Please fill in your details.
                    </p>
                </div>

                <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                    <Input
                        {...register('name')}
                        type="text"
                        label="Full Name"
                        placeholder="Enter your full name"
                        error={errors.name?.message}
                        autoComplete="name"
                    />

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
                        placeholder="Create a password"
                        error={errors.password?.message}
                        showPasswordToggle
                        showPassword={showPassword}
                        onTogglePassword={togglePasswordVisibility}
                        autoComplete="new-password"
                    />

                    <Input
                        {...register('password_confirmation')}
                        type={showConfirmPassword ? 'text' : 'password'}
                        label="Confirm Password"
                        placeholder="Confirm your password"
                        error={errors.password_confirmation?.message}
                        showPasswordToggle
                        showPassword={showConfirmPassword}
                        onTogglePassword={toggleConfirmPasswordVisibility}
                        autoComplete="new-password"
                    />

                    <div className="flex items-center">
                        <input
                            id="terms"
                            name="terms"
                            type="checkbox"
                            className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                            required
                        />
                        <label htmlFor="terms" className="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            I agree to the{' '}
                            <a href="#" className="text-primary-600 hover:text-primary-500">
                                Terms of Service
                            </a>{' '}
                            and{' '}
                            <a href="#" className="text-primary-600 hover:text-primary-500">
                                Privacy Policy
                            </a>
                        </label>
                    </div>

                    <Button
                        type="submit"
                        className="w-full"
                        loading={loading}
                        disabled={loading}
                    >
                        Create Account
                    </Button>
                </form>

                <div className="mt-6 text-center">
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        Already have an account?{' '}
                        <Link
                            to={ROUTES.LOGIN}
                            className="font-medium text-primary-600 hover:text-primary-500"
                        >
                            Sign in
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    )
}

export default RegisterForm