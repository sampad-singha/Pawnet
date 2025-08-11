import React, { useEffect, useState } from 'react';
import { Route, Routes, Navigate } from 'react-router-dom';
import axios from './services/Api.jsx';  // Axios instance
import Home from './pages/Home.jsx';  // Private route
import Login from './pages/Login.jsx';  // Public route
import Register from './pages/Register.jsx';  // Public route
import Dashboard from './pages/Dashboard.jsx';
import GoogleCallback from './components/GoogleCallback.jsx';
import FacebookCallback from './components/FacebookCallback.jsx';  // Private route
import Logout from './components/Logout.jsx';

// Component for handling private routes
const PrivateRoute = ({ children }) => {
    const [auth, setAuth] = useState(null);  // Use null to signify loading
    const [loading, setLoading] = useState(true); // For loading state

    useEffect(() => {
        // Check if the user is authenticated by looking for the token
        const token = localStorage.getItem('authToken');
        if (!token) {
            setAuth(false);  // No token, mark as not authenticated
            setLoading(false); // Done loading
        } else {
            // Optionally check token validity or refresh
            axios.get('/user/verify-token')  // You can replace this with your API route for token verification
                .then(() => {
                    setAuth(true);
                    setLoading(false); // Done loading
                })
                .catch(() => {
                    setAuth(false);
                    setLoading(false); // Done loading
                });
        }
    }, []);

    if (loading) {
        return <div>Loading...</div>;  // Show loading until authentication check is complete
    }

    return auth ? children : <Navigate to="/login" />;  // Redirect to login if not authenticated
};

const Router = () => {
    return (
        <Routes>
            {/* Public routes */}
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/auth/google/callback" element={<GoogleCallback />} />
            <Route path="/auth/facebook/callback" element={<FacebookCallback />} />

            {/* Private routes */}
            <Route path="/dashboard" element={<PrivateRoute><Dashboard /></PrivateRoute>} />
            <Route path="/home" element={<PrivateRoute><Home /></PrivateRoute>} />
            <Route path="/logout" element={<PrivateRoute><Logout /></PrivateRoute>} />

            {/* Redirect to login if no match */}
            <Route path="*" element={<Navigate to="/login" />} />
        </Routes>
    );
};

export default Router;
