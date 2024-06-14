// check if sessionStorage exists
function isAuthenticated() {
    id = sessionStorage.getItem('id');
    role = sessionStorage.getItem('role');
    return (id !== null) && (role !== null);
}

function checkAuthToRedirect() {
    var pathComponents = window.location.pathname.split('/');
    var currentLocation = pathComponents[pathComponents.length - 1];
    if (!isAuthenticated()) { //not logged in, redirect from all protected htm to mlogin.htm
        if (['listing.htm', 'processing.htm', 'manager.htm'].includes(currentLocation)) {
            window.location.href = 'mlogin.htm';
        }//customer redirect to login.htm
        if (['buying.htm'].includes(currentLocation)) {
            window.location.href = 'login.htm';
        }
    } else { //manager logged in, if user go to mlogin or other customer protected htm, redirect to manager.htm
        if (['mlogin.htm', 'buying.htm'].includes(currentLocation) && (role == "manager")) {
            window.location.href = 'manager.htm';
        } //customer loggin, if user go to login.htm or other protected managerial page, redirect to buying.htm
        if (['login.htm', 'manager.htm', 'listing.htm', 'processing.htm'].includes(currentLocation) && (role == "customer")) {
            window.location.href = 'buying.htm';
        }
    }
}

// for Log Out button
function logout() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'logout.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            window.location.href = 'logout.htm';
        }
    };
    xhr.send(null);
}

window.onload = checkAuthToRedirect;