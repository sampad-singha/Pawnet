import React from 'react'
import { Loader2 } from 'lucide-react'

const Loading = ({
                     size = 'md',
                     text = 'Loading...',
                     className = '',
                     fullScreen = false
                 }) => {
    const sizes = {
        sm: 'h-4 w-4',
        md: 'h-6 w-6',
        lg: 'h-8 w-8',
        xl: 'h-12 w-12',
    }

    const content = (
        <div className={`flex flex-col items-center justify-center ${className}`}>
            <Loader2 className={`${sizes[size]} animate-spin text-primary-600`} />
            {text && (
                <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {text}
                </p>
            )}
        </div>
    )

    if (fullScreen) {
        return (
            <div className="fixed inset-0 bg-white dark:bg-gray-900 flex items-center justify-center z-50">
                {content}
            </div>
        )
    }

    return content
}

export default Loading