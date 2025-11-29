import { authAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';
import Swal from 'sweetalert2';

let currentUser = null;

async function loadProfile() {
    try {
        showLoading('Loading profile...');
        const response = await authAPI.getCurrentUser();
        
        currentUser = response.data.data.user;
        
        renderProfile();
        
        closeLoading();
    } catch (error) {
        closeLoading();
        showError('Failed to load profile data');
        console.error(error);
    }
}

function renderProfile() {
    if (!currentUser) return;

    // Update Profile Card
    document.getElementById('profileNameDisplay').textContent = currentUser.name;
    document.getElementById('profileEmailDisplay').textContent = currentUser.email;
    document.getElementById('profileRoleBadge').textContent = currentUser.role === 'decision_maker' ? 'Decision Maker' : 'Admin';
    document.getElementById('profileAvatar').textContent = getInitials(currentUser.name);
    
    const joinedDate = new Date(currentUser.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    document.getElementById('profileJoined').textContent = joinedDate;

    // Update Form Values
    document.getElementById('name').value = currentUser.name;
    document.getElementById('email').value = currentUser.email;
}

async function saveProfile() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;

    // Basic Validation
    if (password && password !== passwordConfirmation) {
        showError('Password confirmation does not match');
        return;
    }

    const payload = {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation
    };

    try {
        showLoading('Saving changes...');
        
        const response = await authAPI.updateProfile(payload);
        
        // Update local state
        currentUser = response.data.data.user;
        
        renderProfile();
        
        // Clear password fields
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
        
        closeLoading();
        showSuccess('Profile updated successfully');
    } catch (error) {
        closeLoading();
        const message = error.response?.data?.message || 'Failed to update profile';
        const errors = error.response?.data?.errors;
        
        if (errors) {
            // Show first validation error
            const firstError = Object.values(errors)[0][0];
            showError(firstError);
        } else {
            showError(message);
        }
    }
}

function getInitials(name) {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadProfile();
    
    document.getElementById('btnSaveProfile')?.addEventListener('click', saveProfile);
});
