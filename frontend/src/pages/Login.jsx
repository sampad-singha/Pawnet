import React, { useState } from 'react';
import { Button, Container, Box, Typography, Input, FormControl, FormLabel, Link } from '@mui/joy';
import api from "../services/Api"; // Import the API service
import GoogleLogin from "../components/GoogleLogin";
import FacebookLogin from "../components/FacebookLogin";
import { useNavigate } from 'react-router-dom'; // Import useNavigate

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false); // For loading state
    const navigate = useNavigate(); // Initialize useNavigate hook

    const handleSubmit = async (e) => {
        e.preventDefault();

        // Clear previous error if any
        setError('');
        setLoading(true); // Start loading

        try {
            // Make the POST request to the /login endpoint
            const response = await api.post('/login', {
                email,
                password
            });

            // Assuming the backend returns a token on successful login
            const { token } = response.data;

            // Store the token in localStorage for further requests
            localStorage.setItem('authToken', token);

            // Clear form fields after successful login
            setEmail('');
            setPassword('');

            // Redirect the user to the dashboard or protected page using useNavigate
            navigate('/dashboard'); // This will trigger client-side navigation without reloading the page
        } catch (err) {
            console.error('Login error:', err);

            // Handle different types of error responses from the API
            if (err.response) {
                // Error response from the server (e.g., invalid credentials, etc.)
                if (err.response.status === 401) {
                    setError('Invalid email or password. Please try again.'); // Clearer error message for 401
                } else if (err.response.status === 500) {
                    setError('Server error. Please try again later.'); // Server error handling
                } else {
                    setError('Something went wrong. Please try again.'); // Generic error handling
                }
            } else if (err.request) {
                // The request was made, but no response was received (network error)
                setError('Network error. Please check your internet connection and try again.');
            } else {
                // Other unexpected errors
                setError('An unexpected error occurred. Please try again.');
            }
        } finally {
            setLoading(false); // Stop loading after the request completes
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
                    <FormControl margin="normal">
                        <FormLabel>Email</FormLabel>
                        <Input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="Enter your email"
                            sx={{ width: '100%' }} // Ensure full width styling
                        />
                    </FormControl>

                    <FormControl margin="normal">
                        <FormLabel>Password</FormLabel>
                        <Input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="Enter your password"
                            sx={{ width: '100%' }} // Ensure full width styling
                        />
                    </FormControl>

                    {error && (
                        <Typography color="error" align="center" marginTop={2}>
                            {error} {/* Display the error message dynamically */}
                        </Typography>
                    )}

                    <Button
                        type="submit"
                        variant="soft"
                        color="primary"
                        sx={{ marginTop: 2 }}
                        disabled={loading} // Disable the button while loading
                    >
                        {loading ? 'Logging in...' : 'Login'}
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
                    <GoogleLogin />
                    <FacebookLogin />
                </Box>
            </Box>
        </Container>
    );
};

export default Login;
