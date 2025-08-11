import * as yup from 'yup'

// Common validation patterns
export const patterns = {
    email: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
    phone: /^[\+]?[1-9][\d]{0,15}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/,
}

// Common validation schemas
export const validationSchemas = {
    // Login schema
    login: yup.object({
        email: yup
            .string()
            .required('Email is required')
            .matches(patterns.email, 'Please enter a valid email'),
        password: yup
            .string()
            .required('Password is required')
            .min(8, 'Password must be at least 8 characters'),
    }),

    // Register schema
    register: yup.object({
        name: yup
            .string()
            .required('Name is required')
            .min(2, 'Name must be at least 2 characters')
            .max(50, 'Name must not exceed 50 characters'),
        email: yup
            .string()
            .required('Email is required')
            .matches(patterns.email, 'Please enter a valid email'),
        password: yup
            .string()
            .required('Password is required')
            .min(8, 'Password must be at least 8 characters')
            .matches(
                patterns.password,
                'Password must contain at least one uppercase letter, one lowercase letter, and one number'
            ),
        password_confirmation: yup
            .string()
            .required('Please confirm your password')
            .oneOf([yup.ref('password')], 'Passwords must match'),
    }),

    // Forgot password schema
    forgotPassword: yup.object({
        email: yup
            .string()
            .required('Email is required')
            .matches(patterns.email, 'Please enter a valid email'),
    }),

    // Reset password schema
    resetPassword: yup.object({
        token: yup.string().required('Reset token is required'),
        email: yup
            .string()
            .required('Email is required')
            .matches(patterns.email, 'Please enter a valid email'),
        password: yup
            .string()
            .required('Password is required')
            .min(8, 'Password must be at least 8 characters')
            .matches(
                patterns.password,
                'Password must contain at least one uppercase letter, one lowercase letter, and one number'
            ),
        password_confirmation: yup
            .string()
            .required('Please confirm your password')
            .oneOf([yup.ref('password')], 'Passwords must match'),
    }),

    // Change password schema
    changePassword: yup.object({
        current_password: yup
            .string()
            .required('Current password is required'),
        password: yup
            .string()
            .required('New password is required')
            .min(8, 'Password must be at least 8 characters')
            .matches(
                patterns.password,
                'Password must contain at least one uppercase letter, one lowercase letter, and one number'
            ),
        password_confirmation: yup
            .string()
            .required('Please confirm your new password')
            .oneOf([yup.ref('password')], 'Passwords must match'),
    }),

    // Profile schema
    profile: yup.object({
        first_name: yup
            .string()
            .required('First name is required')
            .min(2, 'First name must be at least 2 characters')
            .max(30, 'First name must not exceed 30 characters'),
        last_name: yup
            .string()
            .required('Last name is required')
            .min(2, 'Last name must be at least 2 characters')
            .max(30, 'Last name must not exceed 30 characters'),
        email: yup
            .string()
            .required('Email is required')
            .matches(patterns.email, 'Please enter a valid email'),
        phone_number: yup
            .string()
            .nullable()
            .matches(patterns.phone, 'Please enter a valid phone number'),
        date_of_birth: yup
            .date()
            .nullable()
            .max(new Date(), 'Date of birth cannot be in the future'),
        bio: yup
            .string()
            .nullable()
            .max(500, 'Bio must not exceed 500 characters'),
    }),
}

// Helper function to format validation errors
export const formatValidationErrors = (errors) => {
    if (!errors) return {}

    const formattedErrors = {}

    Object.keys(errors).forEach(key => {
        if (Array.isArray(errors[key])) {
            formattedErrors[key] = errors[key][0] // Take first error message
        } else {
            formattedErrors[key] = errors[key]
        }
    })

    return formattedErrors
}