// Display logout message then clear storage
function displayMsg() {
    document.getElementById("message").innerHTML = "Thanks " + sessionStorage.getItem("id") + "!";
    clearLocalStorage();
}

// clear session storage when logout
function clearLocalStorage() {
    sessionStorage.removeItem('role');
    sessionStorage.removeItem('id');
}

window.onload = displayMsg;