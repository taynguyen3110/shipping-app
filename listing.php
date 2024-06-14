<?php

function sanitise_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errMsg = "";
if (isset($_GET["name"]) && isset($_GET["price"]) && isset($_GET["quantity"]) && isset($_GET["desc"])) {
    // Check item name
    $name = $_GET["name"];
    $name = sanitise_input($name);
    if ($name == "")
        $errMsg .= "<p>Please enter Item name! </p>";

    // Check item price
    $price = $_GET["price"];
    if ($price == "")
        $errMsg .= "<p>Please enter Item price! </p>";
    else if ($price <= 0)
        $errMsg .= "<p>Price must be larger than 0! </p>";

    // Check item quantity
    $quantity = $_GET["quantity"];
    if ($quantity == "")
        $errMsg .= "<p>Please enter Item quantity! </p>";
    else if ($quantity <= 0)
        $errMsg .= "<p>Quantity must be larger than 0! </p>";

    // Check desc
    $desc = $_GET["desc"];
    if ($desc == "")
        $errMsg .= "<p>Item description cannot be empty! </p>";

    if ($errMsg != "") { //any errors, echo back to client
        echo $errMsg;
    } else { //no error, create XML item
        $xmlfile = 'data/goods.xml';
        $doc = new DomDocument();
        if (!file_exists($xmlfile)) { // if the xml file does not exist, create a root node $goods
            $goods = $doc->createElement('goods');
            $doc->appendChild($goods);
            //set id for the first item
            $id = 1;
        } else if (file_exists($xmlfile)) {
            $doc->preserveWhiteSpace = FALSE;
            $doc->load($xmlfile);

            //set id = last ID + 1
            $goodsArr = $doc->getElementsByTagName("item");
            $lastItem = $goodsArr->item($goodsArr->length - 1);
            if ($lastItem instanceof DOMElement) {
                $lastID = $lastItem->getElementsByTagName("id")->item(0)->nodeValue;
                $id = $lastID + 1;
            }
        }
        // initialize hold and sold quantity
        $hold_quantity = 0;
        $sold_quantity = 0;

        //create a item node under goods node
        $goods = $doc->getElementsByTagName("goods")->item(0);
        $item = $doc->createElement('item');
        $goods->appendChild($item);

        // create a item ID node ....
        $ID = $doc->createElement('id');
        $item->appendChild($ID);
        $IDValue = $doc->createTextNode($id);
        $ID->appendChild($IDValue);

        // create an Item Name node ....
        $Name = $doc->createElement('name');
        $item->appendChild($Name);
        $NameValue = $doc->createTextNode($name);
        $Name->appendChild($NameValue);

        // create a Item Price node ....
        $Price = $doc->createElement('price');
        $item->appendChild($Price);
        $PriceValue = $doc->createTextNode($price);
        $Price->appendChild($PriceValue);

        //create an Item Hold Quantity node ....
        $HoldQuantity = $doc->createElement('hold_quantity');
        $item->appendChild($HoldQuantity);
        $HQuantityValue = $doc->createTextNode($hold_quantity);
        $HoldQuantity->appendChild($HQuantityValue);

        //create an Item Sold Quantity node ....
        $SoldQuantity = $doc->createElement('sold_quantity');
        $item->appendChild($SoldQuantity);
        $SQuantityValue = $doc->createTextNode($sold_quantity);
        $SoldQuantity->appendChild($SQuantityValue);

        //create an Item Quantity node ....
        $Quantity = $doc->createElement('quantity');
        $item->appendChild($Quantity);
        $quantityValue = $doc->createTextNode($quantity);
        $Quantity->appendChild($quantityValue);

        //create an Item Description node ....
        $Desc = $doc->createElement('description');
        $item->appendChild($Desc);
        $descValue = $doc->createTextNode($desc);
        $Desc->appendChild($descValue);

        //save the xml file
        $doc->formatOutput = true;
        $doc->save($xmlfile);
        echo "The item has been listed in the system, and the item number is: " . $id;
    }
}
