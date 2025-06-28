

import { claerUserInfo, logout, updateUser } from "./api/user.js";


document.addEventListener("DOMContentLoaded", function () {


  const token = localStorage.getItem('token');
  if (!token) {
    alert("يرجى تسجيل الدخول أولاً.");
    window.location.href = "index.html";
    return;
  }


  document.getElementById("user-name").textContent = localStorage.getItem('name');
  document.getElementById("today-date").textContent =
    "تاريخ اليوم: " + new Date().toLocaleDateString("ar-EG");


  const tasksCount = localStorage.getItem("tasks_count") || 0;
  document.getElementById("task-count").textContent = tasksCount;


  const profileImage = document.getElementById("profile-image");
  const deleteBtn = document.getElementById("delete-image");


  if (localStorage.getItem('user_image')) {
    profileImage.src = localStorage.getItem('user_image');
    profileImage.style.display = 'block';
    deleteBtn.style.display = 'block';
  }

  if (deleteBtn) {

    deleteBtn.addEventListener("click", async function () {

      const result = await updateUser({ 'no_image': true });
      if (!result?.data?.user) {
        alert("Something went wrong, check consol...");
        console.log(result);
        return;
      }
      localStorage.removeItem('user_image');
      alert("Profile image has been deleted..");

      profileImage.src = "";
      profileImage.style.display = "none";
      deleteBtn.style.display = "none";
    });

  }


  window.showProfile = function () {
    document.getElementById("profile-container").style.display = "block";
    document.getElementById("settings-container").style.display = "none";
  };

  window.showSettings = function () {
    document.getElementById("profile-container").style.display = "none";
    document.getElementById("settings-container").style.display = "block";
  };

  // Logout
  window.logout = async function () {

    const res = await logout();
    console.log(res);
    // Clear storage
    claerUserInfo();
    // Navigate to login page
    window.location.href = "index.html";
  };

  // Change password
  window.changePassword = function () {
    window.location.href = "forget.html";
  };


 window.addImage = async function () {
  const data = new FormData();
  const imageInput = document.getElementById("profile-image-input");

  if (imageInput.files.length != 0) {
    data.append('image', imageInput.files[0]);
    
  } else {
    alert("Enter image first...");
    return;
    
  }

  const result = await updateUser(data);

  
  if (!result?.data?.user) {
    alert("Something went wrong, check console...");
    console.log("فشل التحديث، الرد من السيرفر:");
    console.log(result);
    return;
  }

  
  console.log(" تم التحديث بنجاح. الرد الكامل:");
  console.log(result);

  console.log(" بيانات المستخدم:");
  console.log("الاسم:", result.data.user.name);
  console.log("البريد:", result.data.user.email);
  console.log("الصورة:", result.data.user.profile_image);
  console.log("معرف المستخدم (ID):", result.data.user.id);

  alert("Profile image has been added..");

  localStorage.setItem("user_image", result.data.user.profile_image);
  profileImage.src = result.data.user.profile_image;
  profileImage.style.display = 'block';
  deleteBtn.style.display = 'block';
}


  window.goToChatPage = function () {
    window.location.href = 'chat.html';
  }
});

