/**
 * SILOKASI Authentication Module
 * Handles JWT authentication, token management, and user session
 */

class AuthManager {
    constructor() {
        this.token = this.getToken();
        this.user = this.getUser();
    }

    /**
     * Get stored JWT token from localStorage
     * @returns {string|null}
     */
    getToken() {
        return localStorage.getItem('token');
    }

    /**
     * Get stored user data from localStorage
     * @returns {object|null}
     */
    getUser() {
        const userStr = localStorage.getItem('user');
        return userStr ? JSON.parse(userStr) : null;
    }

    /**
     * Store JWT token and user data
     * @param {string} token - JWT token
     * @param {object} user - User data
     */
    setAuth(token, user) {
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
        this.token = token;
        this.user = user;
    }

    /**
     * Clear authentication data
     */
    clearAuth() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.token = null;
        this.user = null;
    }

    /**
     * Check if user is authenticated
     * @returns {boolean}
     */
    isAuthenticated() {
        return !!this.token;
    }

    /**
     * Check if user has a specific role
     * @param {string} role - Role to check
     * @returns {boolean}
     */
    hasRole(role) {
        return this.user && this.user.role === role;
    }

    /**
     * Get authorization headers for API requests
     * @returns {object}
     */
    getAuthHeaders() {
        return {
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        };
    }

    /**
     * Make authenticated API request
     * @param {string} url - API endpoint
     * @param {object} options - Fetch options
     * @returns {Promise}
     */
    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: this.getAuthHeaders(),
        };

        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers,
            },
        };

        try {
            const response = await fetch(url, mergedOptions);
            const data = await response.json();

            // Handle token expiration
            if (response.status === 401) {
                this.handleUnauthorized();
                throw new Error('Unauthorized');
            }

            return data;
        } catch (error) {
            console.error('API Request Error:', error);
            throw error;
        }
    }

    /**
     * Handle unauthorized access
     */
    handleUnauthorized() {
        this.clearAuth();
        window.location.href = '/login';
    }

    /**
     * Login user
     * @param {string} email
     * @param {string} password
     * @returns {Promise}
     */
    async login(email, password) {
        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (data.success) {
                this.setAuth(data.access_token, data.user);
            }

            return data;
        } catch (error) {
            console.error('Login Error:', error);
            throw error;
        }
    }

    /**
     * Register new user
     * @param {object} userData - User registration data
     * @returns {Promise}
     */
    async register(userData) {
        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (data.success) {
                this.setAuth(data.access_token, data.user);
            }

            return data;
        } catch (error) {
            console.error('Registration Error:', error);
            throw error;
        }
    }

    /**
     * Logout user
     * @returns {Promise}
     */
    async logout() {
        try {
            await this.apiRequest('/api/auth/logout', {
                method: 'POST'
            });
        } catch (error) {
            console.error('Logout Error:', error);
        } finally {
            this.clearAuth();
            window.location.href = '/login';
        }
    }

    /**
     * Refresh JWT token
     * @returns {Promise}
     */
    async refreshToken() {
        try {
            const data = await this.apiRequest('/api/auth/refresh', {
                method: 'POST'
            });

            if (data.success) {
                this.setAuth(data.access_token, data.user);
            }

            return data;
        } catch (error) {
            console.error('Token Refresh Error:', error);
            throw error;
        }
    }

    /**
     * Get current user profile
     * @returns {Promise}
     */
    async getCurrentUser() {
        try {
            const data = await this.apiRequest('/api/auth/me', {
                method: 'GET'
            });

            if (data.success) {
                this.user = data.user;
                localStorage.setItem('user', JSON.stringify(data.user));
            }

            return data;
        } catch (error) {
            console.error('Get Current User Error:', error);
            throw error;
        }
    }

    /**
     * Check authentication and redirect if necessary
     * @param {boolean} requireAuth - Whether authentication is required
     */
    checkAuth(requireAuth = true) {
        if (requireAuth && !this.isAuthenticated()) {
            window.location.href = '/login';
            return false;
        }

        if (!requireAuth && this.isAuthenticated()) {
            window.location.href = '/dashboard';
            return false;
        }

        return true;
    }

    /**
     * Redirect based on user role
     */
    redirectByRole() {
        if (!this.user) return;

        if (this.user.role === 'admin') {
            window.location.href = '/dashboard';
        } else if (this.user.role === 'decision_maker') {
            window.location.href = '/dashboard';
        }
    }
}

// Create global instance
window.authManager = new AuthManager();

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuthManager;
}
