//
//
//

import { post, get, destroy , put } from './api.js';

//
export function addTask(data) {
    const user_id = localStorage.getItem('user_id') || '';
    return post('/users/' + user_id + '/tasks', data, true);
}
// 
export function updateTask(data, task_id) {
    const user_id = localStorage.getItem('user_id') || '';
    return put('/users/' + user_id + '/tasks/' + task_id, data, true);
}
// 
export function deleteTask(task_id) {
    const user_id = localStorage.getItem('user_id') || '';
    return destroy('/users/' + user_id + '/tasks/' + task_id, {}, true);
}
// 
export function getTasks() {
    const user_id = localStorage.getItem('user_id') || '';
    return get('/users/' + user_id + '/tasks', {}, true);
}