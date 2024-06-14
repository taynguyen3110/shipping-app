// send add item request
function addItem() {
	// prevent Add Item button within the form from submitting the form
	event.preventDefault();

	var name = document.getElementById('name').value;
	var price = document.getElementById('price').value;
    var quantity = document.getElementById('quantity').value;
    var desc = document.getElementById('desc').value;

	xhr.open("GET", "listing.php?name=" + name + "&price=" + price + "&quantity=" + quantity + "&desc=" + desc + "&URLid=" + Number(new Date), true);
	xhr.onreadystatechange = response;
	xhr.send(null);
	
}

// Reset Buttong
function resetInput() {
	// prevent Add Item button to submit the form
	event.preventDefault();
	
	document.getElementById('name').value = "";
	document.getElementById('price').value = "";
    document.getElementById('quantity').value = "";
    document.getElementById('desc').value = "";
}

// response of Add Item request
function response() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById("message").innerHTML = xhr.responseText;
	}
}