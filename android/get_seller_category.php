<?php
/*
 * Following code will get single buyer account
 */
// array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db = new DB_CONNECT();
// check for post data
if (isset($_GET["email"]) &&isset($_GET["password"]))
    {
    $email = $_GET['email'];
    $password=$_GET["password"];
    // get an account
    $sql_command="SELECT *FROM seller_account WHERE email = '$email'";
    $result = mysqli_query($db::$connection,$sql_command) ;
    if (!empty($result))
        {
        // check for empty result
        if (mysqli_num_rows($result) > 0) 
            {
            $result = mysqli_fetch_array($result); 
            $hashed_password=$result['password'];
            //if password match
            if(password_verify($password, $hashed_password))
            {
                //account array
                $account=array();
                $account['id']=$result['id'];
                $account['email']=$result['email'];
                $account['password']=$password;
                $account['establishment']=$result['establishment'];
                $account['establishment_type']=$result['establishment_type'];
		$account['country']=$result['country'];
                $account['location']=$result['location'];
		$account['online']=$result['online'];
		$account['deliver']=$result['deliver'];
                $account['date_added']=$result['date_added'];
                $account['date_changed']=$result['date_changed'];
                //response
                $response["success"] = 1;
                $response["account"] = array();
                array_push($response["account"], $account); 
                echo json_encode($response);
            }
            else 
                {
                $response["success"] = -1;
                $response["message"] = "password ". mysqli_error($db::$connection);
                echo json_encode($response);
                }
            }
            else
                {
                }
    }
    else 
        {
        // no account
        $response["success"] = 0;
        $response["message"] = "no account". mysqli_error($db::$connection);
        echo json_encode($response);
        }
}
else
    {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing". mysqli_error($db::$connection);
    echo json_encode($response);
    }
?>