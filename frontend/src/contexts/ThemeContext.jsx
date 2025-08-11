import React, { createContext, useContext, useEffect, useState } from 'react'
import { THEMES, STORAGE_KEYS } from '@/utils/constants'

const ThemeContext = createContext({
    theme: THEMES.LIGHT,
    toggleTheme: () => {},
    setTheme: () => {},
})

export const useTheme = () => {
    const context = useContext(ThemeContext)
    if (!context) {
        throw new Error('useTheme must be used within a ThemeProvider')
    }
    return context
}

export const ThemeProvider = ({ children }) => {
    const [theme, setThemeState] = useState(() => {
        const savedTheme = localStorage.getItem(STORAGE_KEYS.THEME)
        return savedTheme || THEMES.LIGHT
    })

    useEffect(() => {
        const root = window.document.documentElement

        // Remove previous theme classes
        root.classList.remove(THEMES.LIGHT, THEMES.DARK)

        // Add current theme class
        root.classList.add(theme)

        // Save to localStorage
        localStorage.setItem(STORAGE_KEYS.THEME, theme)
    }, [theme])

    const setTheme = (newTheme) => {
        if (Object.values(THEMES).includes(newTheme)) {
            setThemeState(newTheme)
        }
    }

    const toggleTheme = () => {
        setThemeState(prevTheme =>
            prevTheme === THEMES.LIGHT ? THEMES.DARK : THEMES.LIGHT
        )
    }

    const value = {
        theme,
        setTheme,
        toggleTheme,
    }

    return (
        <ThemeContext.Provider value={value}>
            {children}
        </ThemeContext.Provider>
    )
}