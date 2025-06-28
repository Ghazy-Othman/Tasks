// 
// 
//
import { requestOTPCode } from './api/user.js';

//
document.getElementById("resetForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  //
  const email = document.getElementById("email").value.trim();
  if (!email) {
    alert("يرجى إدخال البريد");
    return;
  }

  //
  const data = {
    'email': email
  };

  //
  const result = await requestOTPCode(data);
  if (!result?.data?.msg) {
    console.log(result);
    alert("Something went wrong, check console...");
    return;
  }

  // Save email just for next page
  localStorage.setItem('user_email', email);
  //
  alert("تم إرسال الرمز إلى بريدك.");
  window.location.href = "verify.html";

  // fetch("", {
  //   method: "POST",
  //   headers: { "Content-Type": "application/json" },
  //   body: JSON.stringify({ email })
  // })
  // .then(response => {
  //   if (response.ok) {
  //     alert("تم إرسال الرمز إلى بريدك.");
  //     window.location.href = "verify-code.html";
  //   } else {
  //     alert("حدث خطأ أثناء إرسال الرمز.");
  //   }
  // })
  // .catch(err => {
  //   alert("تعذر الاتصال بالخادم.");
  //   console.error(err);
  // });


});