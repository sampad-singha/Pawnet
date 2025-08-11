import React from 'react'
import { useForm } from 'react-hook-form'
import { yupResolver } from '@hookform/resolvers/yup'
import { Link } from 'react-router-dom'
import { ArrowLeft } from 'lucide-react'
import { validationSchemas } from '@/utils/validation'
import { authService } from '@/services/authService'
import { ROUTES } from '@/utils/constants'
import Input from '@/components/common/Input'
import Button from '@/components/common/Button'
import ThemeToggle from '@/components/common/ThemeToggle'
import toast from 'react-hot-toast'

const ForgotPassword = () => {
    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
    } = useForm({
        resolver: yupResolver(validationSchemas.forgotPassword),
    })

    const onSubmit = async (data) => {
        try {
            await authService.forgotPassword(data.email)
            toast.success('Password reset link sent to your email!')
        } catch (error) {
            toast.error(error.response?.data?.message || 'Failed to send reset link')
        }
    }

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div className="absolute top-4 right-4">
                <ThemeToggle />
            </div>

            <div className="max-w-md w-full space-y-8">
                <div className="text-center">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Forgot Password
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>

                <div className="bg-white dark:bg-gray-800 shadow-lg rounded-lg px-8 py-6">
                    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                        <Input
                            {...register('email')}
                            type="email"
                            label="Email Address"
                            placeholder="Enter your email"
                            error={errors.email?.message}
                            autoComplete="email"
                        />

                        <Button
                            type="submit"
                            className="w-full"
                            loading={isSubmitting}
                            disabled={isSubmitting}
                        >
                            Send Reset Link
                        </Button>
                    </form>

                    <div className="mt-6 text-center">
                        <Link
                            to={ROUTES.LOGIN}
                            className="inline-flex items-center text-sm text-primary-600 hover:text-primary-500"
                        >
                            <ArrowLeft className="mr-1 h-4 w-4" />
                            Back to Login
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default ForgotPassword