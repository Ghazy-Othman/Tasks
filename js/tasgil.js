


document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem('token');
    const currentPage = window.location.pathname.split("/").pop();

    const authPages = ["index.html", "signup.html", ""];
    if (!token && !authPages.includes(currentPage)) {
        console.log("User not logged in, redirecting to index.html");
        window.location.href = "index.html";
        return;
    }

});
