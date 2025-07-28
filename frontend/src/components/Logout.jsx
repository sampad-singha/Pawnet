import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const Logout = () => {
    const navigate = useNavigate();

    useEffect(() => {
        // Remove the auth token from localStorage to log out the user
        localStorage.removeItem('authToken');  // Or sessionStorage.removeItem('authToken');

        // Optionally, you can also remove other user-related data
        // sessionStorage.removeItem('userData'); // If you store user data in sessionStorage

        // Redirect the user to the login page after logging out
        navigate('/login');  // You can change this to your login route
    }, [navigate]);

    return <div>Logging out...</div>;
};

export default Logout;