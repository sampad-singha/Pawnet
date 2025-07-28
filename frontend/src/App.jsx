// src/App.jsx
import React, { useEffect, useState } from 'react';
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import axios from './Api.jsx';  // Axios instance
import Home from './pages/Home.jsx';  // Private route
import Login from './pages/Login.jsx';  // Public route
import Register from './pages/Register.jsx';  // Public route
import Dashboard from './pages/Dashboard.jsx';
import GoogleCallback from "./components/GoogleCallback.jsx";
import FacebookCallback from "./components/FacebookCallback.jsx";  // Private route

// Component for handling private routes
const PrivateRoute = ({ children }) => {
    const [auth, setAuth] = useState(null);  // Use null to signify loading

    useEffect(() => {
        // Check if the user is authenticated by looking for the token
        const token = localStorage.getItem('authToken');
        if (!token) {
            setAuth(false);  // No token, mark as not authenticated
        } else {
            // Optionally check token validity or refresh
            axios.get('/user/verify-token')  // You can replace this with your API route
                .then(() => setAuth(true))
                .catch(() => setAuth(false));
        }
    }, []);

    if (auth === null) {
        return <div>Loading...</div>;  // Show loading until authentication check is complete
    }

    return auth ? children : <Navigate to="/login" />;  // Redirect to login if not authenticated
};


function App() {
    return (
        <Router>
            <Routes>
                {/* Public routes */}
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route path="/auth/google/callback" element={<GoogleCallback />} />
                <Route path="/auth/facebook/callback" element={<FacebookCallback />} /> {/* Use the same component for Facebook callback */}

                {/* Private routes */}
                <Route
                    path="/dashboard"
                    element={
                        <PrivateRoute>
                            <Dashboard />
                        </PrivateRoute>
                    }
                />
                <Route
                    path="/home"
                    element={
                        <PrivateRoute>
                            <Home />
                        </PrivateRoute>
                    }
                />

                {/* Redirect to login if no match */}
                <Route path="*" element={<Navigate to="/login" />} />
            </Routes>
        </Router>
    );
}

export default App;
