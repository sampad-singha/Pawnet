import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const FacebookCallback = () => {
    const navigate = useNavigate();

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const facebookToken = urlParams.get('token');  // Extract the token from the URL

        if (facebookToken) {
            // Store the JWT token in localStorage
            localStorage.setItem('authToken', facebookToken);
            // Redirect to dashboard after successful login
            navigate('/dashboard');
        }
    }, [navigate]);

    return <div>Loading...</div>;
};

export default FacebookCallback;
