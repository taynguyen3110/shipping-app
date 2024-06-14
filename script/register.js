// Pass user inputs from register page to register.php
function custRegister() {
	var email = document.getElementById('email').value;
	var fname = document.getElementById('fname').value;
	var lname = document.getElementById('lname').value;
	var password = document.getElementById('password').value;
	var rpassword = document.getElementById('rpassword').value;
	var phone = document.getElementById('phone').value;

	xhr.open("GET", "register.php?email=" + encodeURIComponent(email) + "&fname=" + fname + "&lname=" + lname + "&password=" + password + "&rpassword=" + rpassword + "&phone=" + phone + "&id=" + Number(new Date), true);
	xhr.onreadystatechange = response;
	xhr.send(null);

}

// Receive the response display message, if success display login button
function response() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		var responseObj = JSON.parse(xhr.responseText);
		//Display message
		document.getElementById("message").innerHTML = responseObj.message;
		if (responseObj.success) {//Register success
			// Show login button
			document.getElementById("login-btn").style.display = "block";
		}
	}
}

// Hide Loginbtn onload
function hideLoginBtn() {
	document.getElementById("login-btn").style.display = "none";
}

window.onload = hideLoginBtn;