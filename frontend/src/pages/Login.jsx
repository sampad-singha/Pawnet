// Login.jsx

import React, { useState } from 'react';
import { Button, Container, Box, Typography, Input, FormControl, FormLabel, Link } from '@mui/joy';
import { Google as GoogleIcon, Facebook as FacebookIcon } from '@mui/icons-material';
import api from "../Api.jsx"; // Import the icons

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            // Make the POST request to the /login endpoint
            const response = await api.post('login', {
                email,
                password
            });

            // Assuming the backend returns a token on successful login
            const { token } = response.data;

            // Store the token in localStorage or sessionStorage for further requests
            localStorage.setItem('authToken', token);

            // Redirect the user to the dashboard or the protected page
            window.location = '/dashboard';  // Replace with the correct route
        } catch (err) {
            console.error('Login error:', err);
            setError('Invalid credentials, please try again.');
        }
    };

    return (
        <Container
            sx={{
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                height: '100vh',
            }}
        >
            <Box
                sx={{
                    maxWidth: 400,
                    width: '100%',
                    padding: 3,
                    borderRadius: 2,
                    boxShadow: 2,
                    backgroundColor: 'background.paper',
                    border: '1px solid #ddd', // Slim border
                }}
            >
                <Typography variant="h4" align="center" marginBottom={3}>
                    Login
                </Typography>

                <form onSubmit={handleSubmit}>
                    <FormControl  margin="normal">
                        <FormLabel>Email</FormLabel>
                        <Input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="Enter your email"
                            sx={{ width: '100%' }}  // Ensure full width styling
                        />
                    </FormControl>

                    <FormControl  margin="normal">
                        <FormLabel>Password</FormLabel>
                        <Input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="Enter your password"
                            sx={{ width: '100%' }}  // Ensure full width styling
                        />
                    </FormControl>

                    <Button
                        type="submit"
                        
                        variant="soft"
                        color="primary"
                        sx={{ marginTop: 2 }}
                    >
                        Login
                    </Button>

                    <Box sx={{ textAlign: 'center', marginTop: 2 }}>
                        <Link
                            href="/forgot-password"
                            sx={{ display: 'block', marginBottom: 1, fontSize: '0.75rem' }}
                        >
                            Forgot Password?
                        </Link>
                        <Link href="/register" sx={{ fontSize: '0.75rem' }}>
                            Don't have an account? Register
                        </Link>
                    </Box>
                </form>

                <Box
                    sx={{
                        display: 'flex',
                        justifyContent: 'center',
                        marginTop: 3,
                    }}
                >
                    <Button
                        variant="soft"
                        color="google"
                        startDecorator={<GoogleIcon />}
                        sx={{ marginRight: 2 }}
                    >
                        Google
                    </Button>
                    <Button
                        variant="soft"
                        color="facebook"
                        startDecorator={<FacebookIcon />}
                    >
                        Facebook
                    </Button>
                </Box>
            </Box>
        </Container>
    );
};

export default Login;
