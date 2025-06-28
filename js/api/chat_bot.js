//
//
//
import { destroy, get, post } from './api.js';

//
export function getChat() {
    const user_id = localStorage.getItem('user_id');
    return get('/users/' + user_id + '/chat', {}, true)
}
//
export function sendNewMessage(data) {
    const user_id = localStorage.getItem('user_id');
    return post('/users/' + user_id + '/chat/send', data, true);
}
//
export function deleteChat() {
    const user_id = localStorage.getItem('user_id');
    return destroy('/users/' + user_id + '/chat', {}, true);
}