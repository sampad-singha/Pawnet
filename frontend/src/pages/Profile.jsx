import React from 'react'
import { useAuth } from '@/contexts/AuthContext'
import { User, Mail, Calendar, MapPin } from 'lucide-react'

const Profile = () => {
    const { user } = useAuth()

    return (
        <div className="max-w-4xl mx-auto space-y-6">
            {/* Profile Header */}
            <div className="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div className="p-6">
                    <div className="flex items-center space-x-6">
                        <div className="w-20 h-20 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center">
                            <User className="w-10 h-10 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                                {user?.name}
                            </h1>
                            <p className="text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                <Mail className="w-4 h-4 mr-2" />
                                {user?.email}
                            </p>
                            <div className="flex items-center mt-2 space-x-4">
                <span className="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 px-2 py-1 rounded-full text-xs font-medium">
                  Active
                </span>
                                <span className="text-sm text-gray-500 dark:text-gray-400">
                  Member since {new Date().getFullYear()}
                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Profile Information */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Personal Information */}
                <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Personal Information
                    </h2>
                    <div className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Full Name
                            </label>
                            <p className="mt-1 text-sm text-gray-900 dark:text-white">
                                {user?.name || 'Not provided'}
                            </p>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address
                            </label>
                            <p className="mt-1 text-sm text-gray-900 dark:text-white">
                                {user?.email}
                            </p>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number
                            </label>
                            <p className="mt-1 text-sm text-gray-900 dark:text-white">
                                Not provided
                            </p>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Date of Birth
                            </label>
                            <p className="mt-1 text-sm text-gray-900 dark:text-white">
                                Not provided
                            </p>
                        </div>
                    </div>
                </div>

                {/* Account Settings */}
                <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Account Settings
                    </h2>
                    <div className="space-y-4">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-900 dark:text-white">
                                    Profile Visibility
                                </h3>
                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                    Control who can see your profile
                                </p>
                            </div>
                            <select className="mt-1 block w-32 pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option>Public</option>
                                <option>Friends</option>
                                <option>Private</option>
                            </select>
                        </div>
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-900 dark:text-white">
                                    Email Notifications
                                </h3>
                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                    Receive notifications via email
                                </p>
                            </div>
                            <button className="bg-primary-600 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                <span className="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-900 dark:text-white">
                                    Two-Factor Authentication
                                </h3>
                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                    Add an extra layer of security
                                </p>
                            </div>
                            <button className="bg-gray-200 dark:bg-gray-600 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                <span className="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {/* Activity Stats */}
            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Activity Overview
                </h2>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div className="text-center">
                        <div className="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            12
                        </div>
                        <div className="text-sm text-gray-600 dark:text-gray-400">
                            Friends
                        </div>
                    </div>
                    <div className="text-center">
                        <div className="text-2xl font-bold text-green-600 dark:text-green-400">
                            24
                        </div>
                        <div className="text-sm text-gray-600 dark:text-gray-400">
                            Messages
                        </div>
                    </div>
                    <div className="text-center">
                        <div className="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            8
                        </div>
                        <div className="text-sm text-gray-600 dark:text-gray-400">
                            Events
                        </div>
                    </div>
                    <div className="text-center">
                        <div className="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            95%
                        </div>
                        <div className="text-sm text-gray-600 dark:text-gray-400">
                            Activity
                        </div>
                    </div>
                </div>
            </div>

            {/* Action Buttons */}
            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div className="flex flex-wrap gap-4">
                    <button className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                        Edit Profile
                    </button>
                    <button className="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Change Password
                    </button>
                    <button className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    )
}

export default Profile