import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const GoogleCallback = () => {
    const navigate = useNavigate();

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const googleToken = urlParams.get('token');  // Extract the token from URL

        if (googleToken) {
            // Store the JWT token in localStorage
            localStorage.setItem('authToken', googleToken);

            // Redirect to dashboard after successful login
            navigate('/dashboard');
        }
    }, [navigate]);

    return <div>Loading...</div>;
};

export default GoogleCallback;
