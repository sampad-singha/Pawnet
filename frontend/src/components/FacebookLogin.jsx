// In your Login.jsx
import React from 'react';
import axios from '../services/Api.jsx';
import {Button} from "@mui/joy";  // Import your Axios instance
import {Facebook as FacebookIcon } from '@mui/icons-material';

const FacebookLogin = () => {
    const handleFacebookLogin = async () => {
        try {
            const response = await axios.get('/auth/facebook/redirect');
            // Redirect to Facebook OAuth URL
            window.location.href = response.data.url;
        } catch (error) {
            console.error('Error redirecting to Facebook login', error);
        }
    };

    return (
        <Button
            variant="soft"
            color="facebook"
            onClick={handleFacebookLogin}
            startDecorator={<FacebookIcon />}
        >
            Facebook
        </Button>
    );
};
export default FacebookLogin;
