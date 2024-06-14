// send login request to mlogin.php
function managerLogin() {

	var id = document.getElementById('id').value;
	var password = document.getElementById('password').value;
	xhr.open("GET", "mlogin.php?id=" + id + "&password=" + password + "&URLid=" + Number(new Date), true);

	xhr.onreadystatechange = response;
	xhr.send(null);

}

function response() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		var responseObj = JSON.parse(xhr.responseText);
		if (responseObj.success) {//success set sessionStorage and redirect to manager.htm
			// sessionStorage.setItem('authToken', responseObj.token);
			sessionStorage.setItem('id', responseObj.id);
			sessionStorage.setItem('role', 'manager');
			window.location.href = 'manager.htm';
		} else {//Login failed, errMsg != "", display errMsg
			document.getElementById("message").innerHTML = responseObj.message;
		}
	}
}

// function loggedIn() {
// 	if
// }

// window.onload = 
