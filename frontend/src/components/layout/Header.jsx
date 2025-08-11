import React, { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { User, Settings, LogOut, Menu, X } from 'lucide-react'
import { useAuth } from '@/contexts/AuthContext'
import { ROUTES } from '@/utils/constants'
import ThemeToggle from '@/components/common/ThemeToggle'
import Button from '@/components/common/Button'

const Header = () => {
    const [isMenuOpen, setIsMenuOpen] = useState(false)
    const [isDropdownOpen, setIsDropdownOpen] = useState(false)
    const { user, logout } = useAuth()
    const navigate = useNavigate()

    const handleLogout = async () => {
        await logout()
        navigate(ROUTES.LOGIN)
    }

    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen)
    }

    const toggleDropdown = () => {
        setIsDropdownOpen(!isDropdownOpen)
    }

    return (
        <header className="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-16">
                    {/* Logo */}
                    <div className="flex items-center">
                        <Link to={ROUTES.DASHBOARD} className="flex items-center">
                            <div className="bg-primary-600 text-white rounded-lg p-2 mr-3">
                                <User className="h-6 w-6" />
                            </div>
                            <span className="text-xl font-bold text-gray-900 dark:text-white">
                MyApp
              </span>
                        </Link>
                    </div>

                    {/* Desktop Navigation */}
                    <div className="hidden md:flex items-center space-x-4">
                        <nav className="flex space-x-4">
                            <Link
                                to={ROUTES.DASHBOARD}
                                className="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Dashboard
                            </Link>
                            <Link
                                to={ROUTES.PROFILE}
                                className="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Profile
                            </Link>
                        </nav>

                        <ThemeToggle />

                        {/* User Dropdown */}
                        <div className="relative">
                            <button
                                onClick={toggleDropdown}
                                className="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500"
                            >
                                <div className="w-8 h-8 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center">
                                    <User className="w-4 h-4 text-primary-600 dark:text-primary-400" />
                                </div>
                                <span className="ml-2 text-gray-700 dark:text-gray-300 font-medium">
                  {user?.name || 'User'}
                </span>
                            </button>

                            {isDropdownOpen && (
                                <div className="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                                    <div className="py-1">
                                        <Link
                                            to={ROUTES.PROFILE}
                                            className="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                            onClick={() => setIsDropdownOpen(false)}
                                        >
                                            <Settings className="mr-2 h-4 w-4" />
                                            Settings
                                        </Link>
                                        <button
                                            onClick={handleLogout}
                                            className="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            <LogOut className="mr-2 h-4 w-4" />
                                            Logout
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Mobile menu button */}
                    <div className="md:hidden flex items-center space-x-2">
                        <ThemeToggle />
                        <button
                            onClick={toggleMenu}
                            className="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-2"
                        >
                            {isMenuOpen ? (
                                <X className="h-6 w-6" />
                            ) : (
                                <Menu className="h-6 w-6" />
                            )}
                        </button>
                    </div>
                </div>

                {/* Mobile Navigation */}
                {isMenuOpen && (
                    <div className="md:hidden">
                        <div className="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200 dark:border-gray-700">
                            <Link
                                to={ROUTES.DASHBOARD}
                                className="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400"
                                onClick={() => setIsMenuOpen(false)}
                            >
                                Dashboard
                            </Link>
                            <Link
                                to={ROUTES.PROFILE}
                                className="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400"
                                onClick={() => setIsMenuOpen(false)}
                            >
                                Profile
                            </Link>
                            <div className="border-t border-gray-200 dark:border-gray-700 pt-2">
                                <div className="px-3 py-2 text-sm text-gray-600 dark:text-gray-400">
                                    {user?.email}
                                </div>
                                <Button
                                    variant="ghost"
                                    onClick={handleLogout}
                                    className="w-full justify-start px-3 py-2 text-left"
                                >
                                    <LogOut className="mr-2 h-4 w-4" />
                                    Logout
                                </Button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </header>
    )
}

export default Header