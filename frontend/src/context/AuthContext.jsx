import { createContext, useContext, useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import api from "../services/api"; // Axios client (ensure this is correct)
import { setToken, clearToken } from "../services/tokenAuth"; // token handling (ensure file is correctly named)
import { gapi } from "gapi-script"; // Google login

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [initializing, setInitializing] = useState(true);
    const navigate = useNavigate();

    // Initialize Google and Facebook SDKs (only once)
    useEffect(() => {
        // Google login initialization
        gapi.load("client:auth2", () => {
            gapi.auth2.init({
                client_id: import.meta.env.VITE_GOOGLE_CLIENT_ID,
            });
        });
    }, []);

    const fetchUser = useCallback(async () => {
        try {
            const { data } = await api.get("/api/user"); // Make sure this is a valid route
            setUser(data);
        } catch (err) {
            console.error("Error fetching user:", err);
            setUser(null);
        } finally {
            setInitializing(false);
        }
    }, []);

    // Login with Google
    const loginWithGoogle = async () => {
        try {
            const googleAuth = gapi.auth2.getAuthInstance();
            const googleUser = await googleAuth.signIn();
            const token = googleUser.getAuthResponse().id_token;

            // Send token to backend for verification/login
            await api.post("/auth/google", { token });

            setToken(token); // Store token locally
            await fetchUser();
        } catch (error) {
            console.error("Google login error:", error);
        }
    };

    // Login with Facebook
    const loginWithFacebook = async (response) => {
        const { accessToken } = response;
        try {
            await api.post("/auth/facebook", { access_token: accessToken });
            setToken(accessToken); // Store token locally
            await fetchUser();
        } catch (error) {
            console.error("Facebook login error:", error);
        }
    };

    // Login method (for standard login)
    const login = async (email, password) => {
        try {
            await api.get("/sanctum/csrf-cookie"); // Get CSRF token
            const { data } = await api.post("/login", { email, password });
            setToken(data.token); // Store token locally
            await fetchUser();
        } catch (error) {
            console.error("Login error:", error);
        }
    };

    const logout = async () => {
        try {
            await api.post("/logout");
            clearToken(); // Clear stored token
            setUser(null);
            navigate("/login"); // Redirect to login page after logout
        } catch (error) {
            console.error("Logout error:", error);
        }
    };

    const value = {
        user,
        initializing,
        login,
        logout,
        loginWithGoogle,
        loginWithFacebook,
        register: async (payload) => {
            try {
                await api.get("/sanctum/csrf-cookie"); // Get CSRF token
                await api.post("/register", payload);
                await fetchUser();
            } catch (error) {
                console.error("Registration error:", error);
            }
        },
        reloadUser: fetchUser,
    };

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

// eslint-disable-next-line react-refresh/only-export-components
export function useAuth() {
    const ctx = useContext(AuthContext);
    if (!ctx) throw new Error("useAuth must be used within AuthProvider");
    return ctx;
}
