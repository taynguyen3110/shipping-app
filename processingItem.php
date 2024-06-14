<?php
session_start();

$dom = new DOMDocument;
$dom->load("data/goods.xml");
$items = $dom->getElementsByTagName("item");
$revenue = 0;

// loop through sold items id
foreach ($_GET as $good => $id) {
    // loop through item in goods.xml
    foreach ($items as $item) {
        $itemID = $item->getElementsByTagName("id")->item(0)->nodeValue;
        if ($itemID == $id) {
            $itemPrice = $item->getElementsByTagName("price")->item(0)->nodeValue;
            $itemQuantity = $item->getElementsByTagName("quantity")->item(0)->nodeValue;
            $itemHoldQuantity = $item->getElementsByTagName("hold_quantity")->item(0)->nodeValue;
            $itemSoldQuantity = $item->getElementsByTagName("sold_quantity")->item(0);
            $revenue += $itemSoldQuantity->nodeValue * $itemPrice;

            // if avalable quantity == 0, remove item
            if (($itemHoldQuantity == 0) && (($itemQuantity - $itemHoldQuantity - $itemSoldQuantity->nodeValue) == 0)) {
                $item->parentNode->removeChild($item);
            } else {//set sold quantity to 0
                $itemSoldQuantity->nodeValue = 0;
            }
            break;
        }
    }
}

$dom->save("data/goods.xml");
$message = "<p id='item-msg' style='color: #474747;background-color: #bcffc5;'>Goods table has been successfully processed, total revenue is $" . $revenue . "</p>";
echo '{
        "message":"' . $message . '"
    }';
