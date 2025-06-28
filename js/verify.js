// 
// 
// 

import { claerUserInfo, resetPassword } from "./api/user.js";

//
document.getElementById("confirmForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const email = localStorage.getItem("user_email");

  const code = document.getElementById("code").value.trim();
  const newPassword = document.getElementById("newPassword").value;

  if (!code && !newPassword) {
    alert("يرجى تعبئة جميع الحقول");
    return;
  }

  //
  const data = {
    'email': email,
    'new_password': newPassword,
    'new_password_confirmation': newPassword,
    'code': code
  };

  const result = await resetPassword(data);
  if (!result?.data?.msg) {
    console.log(result);
    alert("Something went wrong, check console...");
    return;
  }

  //
  claerUserInfo();
  window.location.href = "reset_sucs.html";


  
});