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
if (isset($_GET["id"]) && isset($_GET["password"])) {

    // Check Manager ID
    $id = $_GET["id"];
    $id = sanitise_input($id);
    if ($id == "")
        $errMsg .= "<p>Please enter your Manager ID!</p>";

    // Check login Password
    $password = $_GET["password"];
    if ($password == "")
        $errMsg .= "<p>Password is required!</p>";

    // CHECK ERRMSG
    if ($errMsg != "") {
        echo jsonFormat($errMsg);
    } else { //Check login information with manager.txt
        $file = "data/manager.txt";
        if (!file_exists($file))
            echo jsonFormat("<p>No registered manager found!</p>");
        else {
            $managers = file($file);
            for ($i = 0; $i < count($managers); $i++) {
                $manager = explode(",", trim($managers[$i]));
                if (($id === $manager[0]) && ($password === trim($manager[1]))) {
                    $_SESSION["id"] = $id;
                    $_SESSION["loggedin"] = true;
                }
            }

            //decide base on login success or not
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) { //success
                // $token = generateUniqueToken();
                // $_SESSION['authToken'] = $token;
                echo '{
                    "success":"true",
                    "id":"' . $id . '",
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
