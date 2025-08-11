import React from 'react'
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import { ThemeProvider } from './contexts/ThemeContext'
import { AuthProvider } from './contexts/AuthContext'
import { ROUTES } from './utils/constants'
import ProtectedRoute from './components/common/ProtectedRoute'
import Layout from './components/layout/Layout'

// Pages
import Login from './pages/auth/Login'
import Register from './pages/auth/Register'
import ForgotPassword from './pages/auth/ForgotPassword'
import ResetPassword from './pages/auth/ResetPassword'
import Dashboard from './pages/Dashboard'
import Profile from './pages/Profile'
import NotFound from './pages/NotFound'

function App() {
    return (
        <ThemeProvider>
            <AuthProvider>
                <Router>
                    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors">
                        <Routes>
                            {/* Public routes */}
                            <Route path={ROUTES.LOGIN} element={<Login />} />
                            <Route path={ROUTES.REGISTER} element={<Register />} />
                            <Route path={ROUTES.FORGOT_PASSWORD} element={<ForgotPassword />} />
                            <Route path={ROUTES.RESET_PASSWORD} element={<ResetPassword />} />

                            {/* Protected routes */}
                            <Route
                                path={ROUTES.HOME}
                                element={
                                    <ProtectedRoute>
                                        <Navigate to={ROUTES.DASHBOARD} replace />
                                    </ProtectedRoute>
                                }
                            />
                            <Route
                                path={ROUTES.DASHBOARD}
                                element={
                                    <ProtectedRoute>
                                        <Layout>
                                            <Dashboard />
                                        </Layout>
                                    </ProtectedRoute>
                                }
                            />
                            <Route
                                path={ROUTES.PROFILE}
                                element={
                                    <ProtectedRoute>
                                        <Layout>
                                            <Profile />
                                        </Layout>
                                    </ProtectedRoute>
                                }
                            />

                            {/* 404 page */}
                            <Route path="*" element={<NotFound />} />
                        </Routes>

                        {/* Toast notifications */}
                        <Toaster
                            position="top-right"
                            toastOptions={{
                                duration: 4000,
                                style: {
                                    background: 'var(--toast-bg)',
                                    color: 'var(--toast-color)',
                                },
                                className: 'dark:bg-gray-800 dark:text-white',
                            }}
                        />
                    </div>
                </Router>
            </AuthProvider>
        </ThemeProvider>
    )
}

export default App