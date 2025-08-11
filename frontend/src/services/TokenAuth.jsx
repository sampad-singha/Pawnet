// Utility functions for managing the token in localStorage
export function setToken(token) {
    localStorage.setItem("auth_token", token);
}

export function clearToken() {
    localStorage.removeItem("auth_token");
}

// You can use this function to get the token if you need it for an API call
export function getToken() {
    return localStorage.getItem("auth_token");
}
