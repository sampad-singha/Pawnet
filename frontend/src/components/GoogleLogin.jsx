import React from 'react';
import {Button} from "@mui/joy";
import { Google as GoogleIcon} from '@mui/icons-material';
import api from "../services/Api.jsx";
import GoogleCallback from "./GoogleCallback.jsx";

const GoogleLogin = () => {
    const handleGoogleLogin = async () => {
        try {
            // Call the backend to get the Google login URL
            const response = await api.get('auth/google/redirect'); // Your Laravel backend URL
            const googleLoginUrl = response.data.url;

            // Redirect to Google login page
            window.location.href = googleLoginUrl;
        } catch (error) {
            console.error("Error during Google login:", error);
        }
    };

    return (
        <Button
            variant="soft"
            color="google"
            startDecorator={<GoogleIcon />}
            onClick={handleGoogleLogin}
            sx={{ marginRight: 2 }}
        >
            Google
        </Button>
    );
};

export default GoogleLogin;
