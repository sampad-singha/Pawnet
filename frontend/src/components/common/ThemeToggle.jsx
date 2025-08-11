import React from 'react'
import { Sun, Moon } from 'lucide-react'
import { useTheme } from '@/contexts/ThemeContext'
import { THEMES } from '@/utils/constants'
import Button from './Button'

const ThemeToggle = ({ className = '' }) => {
    const { theme, toggleTheme } = useTheme()
    const isDark = theme === THEMES.DARK

    return (
        <Button
            variant="ghost"
            size="sm"
            onClick={toggleTheme}
            className={`p-2 ${className}`}
            aria-label={`Switch to ${isDark ? 'light' : 'dark'} theme`}
        >
            {isDark ? (
                <Sun className="h-4 w-4" />
            ) : (
                <Moon className="h-4 w-4" />
            )}
        </Button>
    )
}

export default ThemeToggle