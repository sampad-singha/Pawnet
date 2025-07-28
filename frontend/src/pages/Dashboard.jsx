import React, { useEffect, useState } from 'react';
import api from "../Api.jsx";
import { Link } from 'react-router-dom';

const Dashboard = () => {
    const [user, setUser] = useState(null);

    useEffect(() => {
        const fetchUserProfile = async () => {
            const token = localStorage.getItem('authToken');

            if (token) {
                try {
                    // Make an authenticated request to the backend
                    const response = await api.get('user/', {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    });

                    setUser(response.data); // Set the user data
                } catch (error) {
                    console.error('Error fetching user profile:', error);
                }
            }
        };

        fetchUserProfile();
    }, []);

    return (
        <div>
            {user ? (
                <div>
                    <h1>Welcome, {user.name}!</h1>
                    <p>Email: {user.email}</p>
                    <Link to="/logout">
                        <button>Logout</button>
                    </Link>
                </div>
            ) : (
                <p>Loading user profile...</p>
            )}
        </div>
    );
};

export default Dashboard;
