<?php
session_start();
function sanitise_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errMsg = "";
if (isset($_GET["email"]) && isset($_GET["password"])) {

    // Check Email
    $email = $_GET["email"];
    $email = sanitise_input($email);
    if ($email == "")
        $errMsg .= "<p>Please enter your Email!</p>";

    // Check login Password
    $password = $_GET["password"];
    if ($password == "")
        $errMsg .= "<p>Password is required!</p>";

    // CHECK ERRMSG
    if ($errMsg != "") {
        echo jsonFormat($errMsg);
    } else { //Check login information with customer.xml
        $file = "data/customer.xml";
        if (!file_exists($file))
            echo jsonFormat("<p>No user registered yet!</p>");
        else {
            $dom = new DOMDocument;
            $dom->load($file);
            $customers = $dom->getElementsByTagName("customer");

            foreach ($customers as $customer) {
                $cemail = $customer->getElementsByTagName("email")->item(0)->nodeValue;
                $cpassword = $customer->getElementsByTagName("password")->item(0)->nodeValue;

                if (($email === $cemail) && ($password === $cpassword)) {
                    $_SESSION["loggedin"] = true;
                    break;
                }
            }

            //decide base on login success or not
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) { //success
                // $token = generateUniqueToken();
                // $_SESSION['authToken'] = $token;
                echo '{
                    "success":"true",
                    "id":"' . $email . '",
                    "message":""
                }';
            } else { //failed
                echo jsonFormat("<p>Invalid ID and password!</p>");
            }
        }
    }
}

// function generateUniqueToken()
// {
//     return bin2hex(random_bytes(16));
// }

function jsonFormat($message)
{
    $json = '{
        "success":false,
        "message":"' . $message . '"
    }';
    return $json;
}
