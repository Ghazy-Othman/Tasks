


import { saveUserInfoLocal, signup } from './api/user.js';

//
document.addEventListener("DOMContentLoaded", function () {

    const signupForm = document.getElementById("signup-form");

    if (signupForm) {
        signupForm.addEventListener("submit", async function (e) {

            e.preventDefault();
            //
            const username = document.getElementById("username").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const repeatPassword = document.getElementById("repeat-password").value.trim();

            //
            if (password !== repeatPassword) {
                alert("كلمتا المرور غير متطابقتين");
                return;
            }

            //
            if (username && password) {
                // Data from input
                const data = {
                    'name': username,
                    'email': email,
                    'password': password,
                };

                // Try to register
                const result = await signup(data);
                if (!result?.data) {
                    console.log(result);
                    alert("Something went wrong, check console..");
                    return;
                }

                // Save token and id local 
                saveUserInfoLocal(result.data.token, result.data.user.id, result.data.user.name);

                // Navigate to login page
                alert("تم إنشاء الحساب بنجاح. يرجى تسجيل الدخول.");
                window.location.href = "index.html";

            } else {
                alert("يرجى ملء جميع الحقول");
            }
        });
    }
});
