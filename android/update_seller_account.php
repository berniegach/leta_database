<?php
 
/*
 * Following code will update the seller account
 */
// array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db = new DB_CONNECT();
// check for required fields
if (isset($_POST['id']) && isset($_POST['password'])&& isset($_POST['username']) && isset($_POST['online']) && isset($_POST['deliver']) && isset($_POST['country']) && isset($_POST['location']) 
	&& isset($_POST['order_range']) && isset($_POST['number_of_tables']) )
    {
    $id=$_POST['id'];
    $password=$_POST['password'];
    $username=$_POST['username'];
	$online = $_POST['online'];
	$deliver = $_POST['deliver'];
    $country=$_POST['country'];
    $location=$_POST['location'];
	$order_range=$_POST['order_range'];
	$number_of_tables=$_POST['number_of_tables'];
    //hash the password
    $hashed_password= password_hash($password, PASSWORD_DEFAULT);
    //get current date
    $today= date("d-m-Y H:i");
    // mysql updating a new row
    $sql_command="UPDATE sellers_accounts SET passwords='$hashed_password', usernames='$username', online='$online', deliver='$deliver', country='$country',location='$location', orderrange='$order_range', numberoftables='$number_of_tables', datechanged='$today' WHERE id=$id";
    $result = mysqli_query($db::$connection,$sql_command) ; 
    // check if row updated or not 
    if ($result)
        {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "account successfully updated.";
        echo json_encode($response);
    } 
    else 
        {
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.". mysqli_error($db::$connection);
        echo json_encode($response);
        }
}
else 
    {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
     echo json_encode($response);
    }
?>