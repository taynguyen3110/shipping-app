// response of catalogue table request
function getCatalog() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById("displayItems").innerHTML = xhr.responseText;
	}
}

// request to get catalogue table
function getCatalogData() {
	xhr.open("POST", "buyingCatalog.php", true);
	xhr.onreadystatechange = getCatalog;
	xhr.send("id=" + Number(new Date));
}

// ADD || REMOVE || CANCEL CART || CONFIRM CART requests
function AddRemoveItem(action, id, price) {
	event.preventDefault();
	xhr.open("GET", "buyingCart.php?id=" + id + "&action=" + action + "&price=" + price + "&URLid=" + Number(new Date), true);
	xhr.onreadystatechange = getCart;
	xhr.send(null);
}

// Response for ADD || REMOVE || CANCEL CART || CONFIRM CART requests
function getCart() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		console.log(xhr.responseText);
		var responseObj = JSON.parse(xhr.responseText);
		var cart = document.getElementById("cart");
		// console.log(responseObj);
		cart.innerHTML = "";
		document.getElementById("message").innerHTML = "";
		if (responseObj.message != "") {
			document.getElementById("message").innerHTML = responseObj.message;
			var itemMsg = document.getElementById("message");
			if (itemMsg) {
				itemMsg.scrollIntoView({ behavior: "smooth", block: "start" });
				itemMsg.focus();
			}
		}
		if (Object.keys(responseObj.cart).length > 1) {
			var HTML = "<h2 style='text-align:center; margin: 10px 0 5px 0;'>Shopping Cart</h2>";
			HTML += "<table id='cartTable'><thead><tr><th>Item Number</th><th>Price</th><th>Quantity</th><th>Remove</th></tr></thead><tbody>";
			Object.keys(responseObj.cart).forEach(function (id) {
				if (id !== "total") {
					HTML += "<tr><td>" + id + "</td>";
					HTML += "<td>" + responseObj.cart[id].price + "</td>";
					HTML += "<td>" + responseObj.cart[id].quantity + "</td>";
					HTML += "<td><a class='remove-button' href='#' onclick='AddRemoveItem(\"Remove\", " + id + ", " + responseObj.cart[id].price + ");'>Remove Item</a></td></tr>";
				} else {
					HTML += "<tr><td colspan='3' style='font-weight:bold;'>Total</td><td style='color:black; font-weight:bold;'>$" + responseObj.cart[id] + "</td></tr>";
					HTML += "<tr style='background-color: #f2f2f2;'><td colspan='2' style='text-align:center;'><a class='confirm-button' href='#' onclick='AddRemoveItem(\"Confirm\", 0, 0);'>Confirm Purchase</a></td>";
					HTML += "<td colspan='2' style='text-align:center;'><a class='cancel-button' href='#' onclick='AddRemoveItem(\"Cancel\", 0, 0);'>Cancel Purchase</a></td></tr>";
				}
			});
			HTML += "</tbody></table>";
			cart.innerHTML = HTML;
		}
	}
}

function loadCatalog() {
	checkAuthToRedirect();
	getCatalogData(); //Display catalogue
	setInterval(getCatalogData, 5000); //refresh every 5 seconds
}

window.onload = loadCatalog;