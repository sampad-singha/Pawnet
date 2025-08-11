import React, { useState, useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { yupResolver } from '@hookform/resolvers/yup'
import { useSearchParams, useNavigate } from 'react-router-dom'
import { validationSchemas } from '@/utils/validation'
import { authService } from '@/services/authService'
import { ROUTES } from '@/utils/constants'
import Input from '@/components/common/Input'
import Button from '@/components/common/Button'
import ThemeToggle from '@/components/common/ThemeToggle'
import toast from 'react-hot-toast'

const ResetPassword = () => {
    const [searchParams] = useSearchParams()
    const navigate = useNavigate()
    const [showPassword, setShowPassword] = useState(false)
    const [showConfirmPassword, setShowConfirmPassword] = useState(false)

    const token = searchParams.get('token')
    const email = searchParams.get('email')

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
        setValue,
    } = useForm({
        resolver: yupResolver(validationSchemas.resetPassword),
    })

    useEffect(() => {
        if (!token || !email) {
            toast.error('Invalid reset link')
            navigate(ROUTES.LOGIN)
            return
        }

        setValue('token', token)
        setValue('email', email)
    }, [token, email, setValue, navigate])

    const onSubmit = async (data) => {
        try {
            await authService.resetPassword(data)
            toast.success('Password reset successfully!')
            navigate(ROUTES.LOGIN)
        } catch (error) {
            toast.error(error.response?.data?.message || 'Failed to reset password')
        }
    }

    if (!token || !email) {
        return null
    }

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div className="absolute top-4 right-4">
                <ThemeToggle />
            </div>

            <div className="max-w-md w-full space-y-8">
                <div className="text-center">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Reset Password
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Enter your new password below.
                    </p>
                </div>

                <div className="bg-white dark:bg-gray-800 shadow-lg rounded-lg px-8 py-6">
                    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                        <input type="hidden" {...register('token')} />
                        <input type="hidden" {...register('email')} />

                        <Input
                            {...register('password')}
                            type={showPassword ? 'text' : 'password'}
                            label="New Password"
                            placeholder="Enter new password"
                            error={errors.password?.message}
                            showPasswordToggle
                            showPassword={showPassword}
                            onTogglePassword={() => setShowPassword(!showPassword)}
                            autoComplete="new-password"
                        />

                        <Input
                            {...register('password_confirmation')}
                            type={showConfirmPassword ? 'text' : 'password'}
                            label="Confirm New Password"
                            placeholder="Confirm new password"
                            error={errors.password_confirmation?.message}
                            showPasswordToggle
                            showPassword={showConfirmPassword}
                            onTogglePassword={() => setShowConfirmPassword(!showConfirmPassword)}
                            autoComplete="new-password"
                        />

                        <Button
                            type="submit"
                            className="w-full"
                            loading={isSubmitting}
                            disabled={isSubmitting}
                        >
                            Reset Password
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    )
}

export default ResetPassword
