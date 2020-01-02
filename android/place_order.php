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
//get the new table name
function makeTableName($id)
{
    $array= str_split($id);
    $name='';
    for($count=0; $count<sizeof($array); $count++)
    {
        switch ($array[$count])
        {
            case 0:
                $name=$name.'zero';
                break;
            case 1:
                $name=$name.'one';
                break;
            case 2:
                $name=$name.'two';
                break;
            case 3:
                $name=$name.'three';
                break;
            case 4:
                $name=$name.'four';
                break;
            case 5:
                $name=$name.'five';
                break;
            case 6:
                $name=$name.'six';
                break;
            case 7:
                $name=$name.'seven';
                break;
            case 8:
                $name=$name.'eight';
                break;
            case 9:
                $name=$name.'nine';
                break;
            default :
                $name=$name.'NON';
        }
    }
    return $name;    
}

// check for post data
if (isset($_POST["seller_id"]) && isset($_POST["buyer_id"]) && isset($_POST["items_ids"]) && isset($_POST["table_number"]))
    {
	//$country=$_POST["country"]
	$seller_id=$_POST["seller_id"];
	$buyer_id=$_POST["buyer_id"];
	$items_ids=$_POST["items_ids"];
	$table_number=$_POST["table_number"];
	$header=makeTableName((string)$seller_id);
	$table_name=$header."_orders";
	//get a unique number for the day
	$sql_command_last="SELECT * FROM $table_name ORDER BY id DESC LIMIT 1";
	$result_last = mysqli_query($db::$connection,$sql_command_last) ;
	if(!$result_last || mysqli_num_rows($result_last) <=0)
		$order_number=1;
	else
	{
		//the table is not empty do we get the unique for the day or we start again if this is a new day
		$result_last = mysqli_fetch_array($result_last); 
		$order_number=$result_last['ordernumber'];
		$date_added=$result_last['dateadded'];
		//dateadded is in the form date("d-m-Y H:i");
		$date_added_pieces=explode(" ",$date_added);
		//$date_added_pieces=explode("-",$date_added_pieces[0]);
		$today= date("d-m-Y");
		//$today_pieces=explode("-",$today);
		//todays year can either be greater or equal to date_added 
		//if the first condition is false, meaning the year is equal we move on to the month which...
		//todays month can either be greater or equal to date_added
		//if the second condition is false, meaning the month is same we move on to the day whereby we just increment the order_number
		if($date_added_pieces[0]==$today)
			$order_number=$order_number+1;
		else
			$order_number=1;			
	}
	$today= date("d-m-Y H:i");
	$items_ids_pieces=explode(",",$items_ids);
	for($c=0; $c<count($items_ids_pieces); $c++)
	{
		//insert the order
		$sql_command_add="INSERT INTO $table_name(userid, itemid, ordernumber, orderstatus, tablenumber, dateadded) VALUES('$buyer_id', '$items_ids_pieces[$c]', '$order_number', '1', '$table_number', '$today' )";
		$result_add = mysqli_query($db::$connection,$sql_command_add) ;
		if(!$result_add)
		{
			$response["success"] = -1;
			$response["message"] = "error inserting order.". mysqli_error($db::$connection);
			echo json_encode($response);
		}
	}
	$header_buyer=makeTableName((string)$buyer_id);
	//insert the order info
	$sql_command_orders="SELECT *FROM buyers_accounts WHERE id = '$buyer_id'";
	$result_orders = mysqli_query($db::$connection,$sql_command_orders) ;
	$result_orders = mysqli_fetch_array($result_orders);
	$orders=$result_orders['orders'];
	if($orders=="NULL" || $orders=="null" || $orders=="")
	{
		$orders=$seller_id.','.$order_number.','.$today;
	}
	else
	{
		$orders=$orders.';'.$seller_id.','.$order_number.','.$today;
	}
	$sql_command_insert_order="UPDATE buyers_accounts SET orders ='$orders', datechanged = '$today' WHERE id = '$buyer_id'";
	$result_insert_order = mysqli_query($db::$connection,$sql_command_insert_order) ;
	if(!$result_insert_order)
	{
		$response["success"] = -2;
		$response["message"] = "error inserting order.". mysqli_error($db::$connection);
		echo json_encode($response);
	}
	//insert a notification
	$table_name_notification=$header_buyer.'_b_notifications';
	$message="Your order has been placed.\n your order number is $order_number.\n Please wait.";
	$sql_command_insert="INSERT INTO $table_name_notification (classes,messages,dateadded) VALUES('0', '$message', '$today')";
	$result_insert = mysqli_query($db::$connection,$sql_command_insert) ;
	if(!$result_insert)
	{
		$response["success"] = -3;
		$response["message"] = "error inserting notification.". mysqli_error($db::$connection);
		echo json_encode($response);
	}
	$response["success"] = 1;
	echo json_encode($response);	

}
else
    {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing". mysqli_error($db::$connection);
    echo json_encode($response);
    }
?>