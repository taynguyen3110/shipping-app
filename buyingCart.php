<?php
session_start();

$id = $_GET["id"];
$action = $_GET["action"];
$price = $_GET["price"];
$message = "";

if (!isset($_SESSION["Cart"])) {
    $_SESSION["Cart"] = [];
    $_SESSION["total"] = 0;
}

// retrieve cart info from session
$Cart = $_SESSION["Cart"];
$total = $_SESSION["total"];

// load goods.xml
$dom = new DOMDocument;
$dom->load("data/goods.xml");
$items = $dom->getElementsByTagName("item");

if ($action == "Cancel") { //CANCEL CART
    foreach ($Cart as $a => $b) {
        // Update XML
        foreach ($items as $item) {
            $itemID = $item->getElementsByTagName("id")->item(0)->nodeValue;
            if ($itemID == $a) {
                $itemHoldQuantity = $item->getElementsByTagName("hold_quantity")->item(0);
                $itemHoldQuantity->nodeValue -= $Cart[$a]["quantity"];
                break;
            }
        }
    }
    // Update Cart
    $Cart = [];
    $total = 0;
    $message = "<p id='item-msg' style='color: #474747;background-color: #eeedff;'>Your purchase request has been cancelled, welcome to shop next time!</p>";
} else if ($action == "Confirm") { //CONFIRM PURCHASE
    foreach ($Cart as $a => $b) {
        // Update XML
        foreach ($items as $item) {
            $itemID = $item->getElementsByTagName("id")->item(0)->nodeValue;
            if ($itemID == $a) {
                $itemHoldQuantity = $item->getElementsByTagName("hold_quantity")->item(0);
                $itemSoldQuantity = $item->getElementsByTagName("sold_quantity")->item(0);
                $itemHoldQuantity->nodeValue -= $Cart[$a]["quantity"];
                $itemSoldQuantity->nodeValue += $Cart[$a]["quantity"];
                break;
            }
        }
    }
    // Update Cart
    $Cart = [];
    $message = "<p id='item-msg' style='color: #474747;background-color: #bcffc5;'>Your purchase has been confirmed and total amount due to pay is $" . $total . "</p>";
    echo '{
        "cart": ' . json_encode($Cart) . ',
        "message":"' . $message . '"
    }';
    $total = 0;
    $_SESSION["Cart"] = $Cart;
    $_SESSION["total"] = $total;

    $dom->save("data/goods.xml");
    //process stop here if confirm purchase case
    exit();
    
} else {//ADD, REMOVE ITEM
    //Get quantity node elements
    foreach ($items as $item) {
        $itemID = $item->getElementsByTagName("id")->item(0)->nodeValue;
        if ($itemID === $id) {
            $itemQuantity = $item->getElementsByTagName("quantity")->item(0);
            $itemHoldQuantity = $item->getElementsByTagName("hold_quantity")->item(0);
            $itemSoldQuantity = $item->getElementsByTagName("sold_quantity")->item(0);
            break;
        }
    }

    //Check action, then update quantity in XML files and update Cart
    if ($_SESSION["Cart"] != "") { //Cart is not empty
        if ($action == "Add") { //ADD ITEM
            if (($itemQuantity->nodeValue - $itemHoldQuantity->nodeValue - $itemSoldQuantity->nodeValue) <= 0) { //Item not available
                $message = "<p id='item-msg'>Sorry, item " . $id . " is not available for sale!</p>";
            } else { //Item available
                // Update XML
                $itemHoldQuantity->nodeValue += 1;
                // Update Cart
                if (array_key_exists($id, $Cart)) { //Item $id existed, add 1 more to item in Cart
                    $Cart[$id]["quantity"]++;
                    $total += $price;
                } else { //Item $id not existed, added first item $id in Cart
                    $Cart[$id]["price"] = $price;
                    $Cart[$id]["quantity"] = 1;
                    $total += $price;
                }
            }
        } else if ($action == "Remove") { //REMOVE ITEM FROM CART
            // Update XML
            $itemHoldQuantity->nodeValue -= $Cart[$id]["quantity"];
            // Update Cart
            $total -= $price * $Cart[$id]["quantity"];
            unset($Cart[$id]);
        }
    } else { //Cart is empty, first item of the cart
        // Update XML
        $itemHoldQuantity->nodeValue += 1;
        // Update Cart
        $Cart[$id]["price"] = $price;
        $Cart[$id]["quantity"] = 1;
        $total = $price;
    }
}
//Update session
$_SESSION["Cart"] = $Cart;
$_SESSION["total"] = $total;

$Cart["total"] = $total;
// save goods.xml
$dom->save("data/goods.xml");

echo '{
    "cart": ' . json_encode($Cart) . ',
    "message":"' . $message . '"
}';
