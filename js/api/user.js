//
//
//
import { get, post, put } from './api.js';

//
export function signup(data) {
    return post('/users/auth/register', data, false);
}
//
export function login(data) {
    return post('/users/auth/login', data, false);
}
//
export function logout() {
    return post('/users/auth/logout', {}, true);
}
//
export function getUser() {
    const user_id = localStorage.getItem('user_id') || '';
    return get('/users/' + user_id, true);
}
//
export function updateUser(data) {
    const user_id = localStorage.getItem('user_id') || '';
    return post('/users/' + user_id, data, true);
}
//
export function saveUserInfoLocal(token, user_id, name , image) {
    localStorage.setItem('token', token);
    localStorage.setItem('name', name);
    localStorage.setItem('user_id', user_id);
    localStorage.setItem('user_image', image);
}
//
export function claerUserInfo() {
    localStorage.removeItem('token');
    localStorage.removeItem('name');
    localStorage.removeItem('user_id');
    localStorage.removeItem('user_image');
}
/**
 * ======================================
 *  Password & OTP Code
 * ======================================
 */
//
export function requestOTPCode(data) {
    return post('/users/request-otp-code', data, false);
}
//
export function resetPassword(data) {
    return post('/users/reset-password', data, false);
}
//
export function changePassword(data) {
    return post('/users/change-password', data, false);
}
