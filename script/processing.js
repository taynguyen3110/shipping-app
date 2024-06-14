// get item table
function getData() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById("itemTable").innerHTML = xhr.responseText;
	}
}

// send request to get sold item tables
function getItemData() {
	xhr.open("POST", "processingItemTable.php", true);
	xhr.onreadystatechange = getData;
	xhr.send("id=" + Number(new Date));
}

// send process request
function processItem() {
	event.preventDefault();
	var stringURL = "";
	// get all sold items id displayed on client side, coonstruct a URL string
	var id = document.querySelectorAll(".id");
	for (var i = 0; i < id.length; i++) {
		stringURL += "item" + [i + 1] + "=" + id[i].textContent + "&";
		console.log(stringURL);
	}
	
	xhr.open("GET", "processingItem.php?" + stringURL, true);
	xhr.onreadystatechange = processTable;
	xhr.send(null);
}

// process sold item
function processTable() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		console.log(xhr.responseText);
		var responseObj = JSON.parse(xhr.responseText);
		if (responseObj.message != "") {
			document.getElementById("message").innerHTML = responseObj.message;
			var itemMsg = document.getElementById("message");
			if (itemMsg) {
				itemMsg.scrollIntoView({ behavior: "smooth", block: "start" });
				itemMsg.focus();
			}
		}
		getItemData();
	}
}

// Load sold items table onload
function loadItemTable() {
	checkAuthToRedirect();
	getItemData();
}

window.onload = loadItemTable;