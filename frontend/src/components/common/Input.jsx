import React, { forwardRef } from 'react'
import { Eye, EyeOff } from 'lucide-react'

const Input = forwardRef(({
                              label,
                              type = 'text',
                              error,
                              className = '',
                              showPasswordToggle = false,
                              onTogglePassword,
                              showPassword = false,
                              ...props
                          }, ref) => {
    const baseClasses = 'block w-full px-3 py-2 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors'

    const errorClasses = error
        ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500'
        : 'border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white'

    const classes = `${baseClasses} ${errorClasses} ${className}`

    return (
        <div className="w-full">
            {label && (
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {label}
                </label>
            )}
            <div className="relative">
                <input
                    ref={ref}
                    type={type}
                    className={classes}
                    {...props}
                />
                {showPasswordToggle && (
                    <button
                        type="button"
                        className="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onClick={onTogglePassword}
                    >
                        {showPassword ? (
                            <EyeOff className="h-4 w-4 text-gray-400 hover:text-gray-600" />
                        ) : (
                            <Eye className="h-4 w-4 text-gray-400 hover:text-gray-600" />
                        )}
                    </button>
                )}
            </div>
            {error && (
                <p className="mt-1 text-sm text-red-600 dark:text-red-400">
                    {error}
                </p>
            )}
        </div>
    )
})

Input.displayName = 'Input'

export default Input