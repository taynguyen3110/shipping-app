<?php

function sanitise_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errMsg = "";
if (isset($_GET["email"]) && isset($_GET["fname"]) && isset($_GET["lname"]) && isset($_GET["password"]) && isset($_GET["rpassword"]) && isset($_GET["phone"])) {

    $xmlfile = 'data/customer.xml';
    $doc = new DomDocument();

    // Check Email Address
    $email = $_GET["email"];
    $email = sanitise_input($email);
    if ($email == "")
        $errMsg .= "<p>Please enter your Email Address! </p>";
    else {
        if (file_exists($xmlfile)) { //if customer.xml exists, check email unique, then generate customer ID
            //load xml file
            $doc->preserveWhiteSpace = FALSE;
            $doc->load($xmlfile);

            //Compare email with each customer in customer.xml
            $existed = false;
            $custs = $doc->getElementsByTagName("customer");
            foreach ($custs as $cust) {
                if ($cust->getElementsByTagName("email")->item(0)->nodeValue == $email) {
                    $existed = true;
                    $errMsg .= "<p>Email already exists! </p>";
                }
            }
            //id = last ID + 1, there is no delete customer, increment ids will make sure it unique
            $lastCustomer = $custs->item($custs->length - 1);
            if ($lastCustomer instanceof DOMElement) {
                $lastID = $lastCustomer->getElementsByTagName("id")->item(0)->nodeValue;
                $id = $lastID + 1;
            }
        }
    }

    // Check register First Name
    $fname = $_GET["fname"];
    $fname = sanitise_input($fname);
    if ($fname == "")
        $errMsg .= "<p>Please enter your First Name! </p>";

    // Check register Last Name
    $lname = $_GET["lname"];
    $lname = sanitise_input($lname);
    if ($lname == "")
        $errMsg .= "<p>Please enter your Last Name! </p>";

    // Check register Password
    $password = $_GET["password"];
    if ($password == "")
        $errMsg .= "<p>Password is required! </p>";


    // Check Re-typed Password
    $rpassword = $_GET["rpassword"];
    if ($rpassword !== $password)
        $errMsg .= "<p>Password does not match! </p>";

    // Check Contact Phone
    $phone = $_GET["phone"];
    $phone = sanitise_input($phone);
    if ($phone != "") {
        if (!preg_match("/^(?:0\d\s|\(0\d\))\d{8}$/", $phone))
            $errMsg .= "<p>Phone number must follow (0d)dddddddd or 0d dddddddd! </p>";
    }

    if ($errMsg != "") { //any errors, return back to client
        echo jsonFormat($errMsg);
    } else {
        if (!file_exists($xmlfile)) { // if the xml file does not exist, create a root node $customers
            $customers = $doc->createElement('customers');
            $doc->appendChild($customers);
            //set id for the first customer
            $id = 1;
        }

        //create a customer node under customers node
        $customers = $doc->getElementsByTagName('customers')->item(0);
        $customer = $doc->createElement('customer');
        $customers->appendChild($customer);

        // create a customer ID node ....
        $ID = $doc->createElement('id');
        $customer->appendChild($ID);
        $IDValue = $doc->createTextNode($id);
        $ID->appendChild($IDValue);

        // create a First Name node ....
        $Fname = $doc->createElement('firstname');
        $customer->appendChild($Fname);
        $FnameValue = $doc->createTextNode($fname);
        $Fname->appendChild($FnameValue);

        // create a surname node ....
        $Lname = $doc->createElement('surname');
        $customer->appendChild($Lname);
        $LnameValue = $doc->createTextNode($lname);
        $Lname->appendChild($LnameValue);

        //create a Email Address node ....
        $Email = $doc->createElement('email');
        $customer->appendChild($Email);
        $emailValue = $doc->createTextNode($email);
        $Email->appendChild($emailValue);

        //create a password node ....
        $pwd = $doc->createElement('password');
        $customer->appendChild($pwd);
        $pwdValue = $doc->createTextNode($password);
        $pwd->appendChild($pwdValue);

        // create a Contact Phone number node ....
        $Phone = $doc->createElement('phone');
        $customer->appendChild($Phone);
        $phoneValue = $doc->createTextNode($phone);
        $Phone->appendChild($phoneValue);

        //save the xml file
        $doc->formatOutput = true;
        $doc->save($xmlfile);

        // return the message to client in JSON format
        echo '{
            "success":true,
            "message":"' . 'Dear ' . $fname . ', you have successfully registered using the email ' . $email . '"
        }';
    }
}

//JSON format function for errMsg
function jsonFormat($message)
{
    $json = '{
        "success":false,
        "message":"' . $message . '"
    }';
    return $json;
}
