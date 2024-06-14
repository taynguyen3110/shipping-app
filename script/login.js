// send login request to login.php
function customerLogin() {

	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	xhr.open("GET", "login.php?email=" + email + "&password=" + password + "&id=" + Number(new Date), true);

	xhr.onreadystatechange = response;
	xhr.send(null);

}

// process response
function response() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		console.log(xhr.responseText);
		var responseObj = JSON.parse(xhr.responseText);
		if (responseObj.success) {//set sessionStorage and redirect to buying.htm
			sessionStorage.setItem('id', responseObj.id);
			sessionStorage.setItem('role', 'customer');
			window.location.href = 'buying.htm';
		} else {//Login failed, errMsg != "", display errMsg
			document.getElementById("message").innerHTML = responseObj.message;
		}
	}
}