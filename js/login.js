// 
// 
// 

import { saveUserInfoLocal, login } from './api/user.js';

document.addEventListener('DOMContentLoaded', function () {

    const loginForm = document.getElementById('login-form');

    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {

            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (email === '' || password === '') {
                alert("يرجى إدخال الإيميل و كلمة المرور.");
                return;
            }

            // Data from input
            const data = {
                'email': email,
                'password': password
            };

            // Try to login
            const result = await login(data);
            if (!result?.data) {
                console.log(result);
                alert("Something went wrong, check console..");
                return;
            }

            console.log(result) ; 

            // Save token and id local 
            saveUserInfoLocal(result.data.token, result.data.user.id, result.data.user.name, result.data.user.profile_image);

            // Navigate to next page
            alert("تم تسجيل الدخول بنجاح");
            window.location.href = "min.html";
        });
    }
});
