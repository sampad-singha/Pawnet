import React from 'react'
import { useAuth } from '@/contexts/AuthContext'
import { Users, MessageSquare, Calendar, TrendingUp } from 'lucide-react'

const Dashboard = () => {
    const { user } = useAuth()

    const stats = [
        {
            name: 'Total Friends',
            value: '12',
            icon: Users,
            color: 'bg-blue-500',
        },
        {
            name: 'Messages',
            value: '24',
            icon: MessageSquare,
            color: 'bg-green-500',
        },
        {
            name: 'Events',
            value: '8',
            icon: Calendar,
            color: 'bg-yellow-500',
        },
        {
            name: 'Activity',
            value: '95%',
            icon: TrendingUp,
            color: 'bg-purple-500',
        },
    ]

    return (
        <div className="space-y-6">
            {/* Welcome Section */}
            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                            Welcome back, {user?.name}!
                        </h1>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Here's what's happening with your account today.
                        </p>
                    </div>
                    <div className="hidden sm:block">
                        <div className="w-16 h-16 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center">
                            <Users className="w-8 h-8 text-primary-600 dark:text-primary-400" />
                        </div>
                    </div>
                </div>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {stats.map((stat, index) => (
                    <div
                        key={index}
                        className="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-lg transition-shadow"
                    >
                        <div className="flex items-center">
                            <div className={`${stat.color} rounded-lg p-3`}>
                                <stat.icon className="w-6 h-6 text-white" />
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {stat.name}
                                </p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    {stat.value}
                                </p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Recent Activity */}
            <div className="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div className="p-6">
                    <h2 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Recent Activity
                    </h2>
                    <div className="space-y-4">
                        <div className="flex items-start space-x-3">
                            <div className="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p className="text-sm text-gray-900 dark:text-white">
                                    Your profile was updated successfully
                                </p>
                                <p className="text-xs text-gray-500 dark:text-gray-400">
                                    2 hours ago
                                </p>
                            </div>
                        </div>
                        <div className="flex items-start space-x-3">
                            <div className="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p className="text-sm text-gray-900 dark:text-white">
                                    New friend request received
                                </p>
                                <p className="text-xs text-gray-500 dark:text-gray-400">
                                    1 day ago
                                </p>
                            </div>
                        </div>
                        <div className="flex items-start space-x-3">
                            <div className="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p className="text-sm text-gray-900 dark:text-white">
                                    Password changed successfully
                                </p>
                                <p className="text-xs text-gray-500 dark:text-gray-400">
                                    3 days ago
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Quick Actions */}
            <div className="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Quick Actions
                </h2>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button className="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <Users className="w-6 h-6 text-primary-600 dark:text-primary-400 mb-2" />
                        <span className="text-sm font-medium text-gray-900 dark:text-white">
              Find Friends
            </span>
                    </button>
                    <button className="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <MessageSquare className="w-6 h-6 text-primary-600 dark:text-primary-400 mb-2" />
                        <span className="text-sm font-medium text-gray-900 dark:text-white">
              Messages
            </span>
                    </button>
                    <button className="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <Calendar className="w-6 h-6 text-primary-600 dark:text-primary-400 mb-2" />
                        <span className="text-sm font-medium text-gray-900 dark:text-white">
              Events
            </span>
                    </button>
                    <button className="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <TrendingUp className="w-6 h-6 text-primary-600 dark:text-primary-400 mb-2" />
                        <span className="text-sm font-medium text-gray-900 dark:text-white">
              Analytics
            </span>
                    </button>
                </div>
            </div>
        </div>
    )
}

export default Dashboard