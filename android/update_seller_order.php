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
// check for required fields
if (isset($_POST['id']) && isset($_POST['buyer_id']) && isset($_POST['order_id']) && isset($_POST['order_number'])&& isset($_POST['status']) && isset($_POST['date_added'])  )
    {
    $id=$_POST['id'];
	$buyer_id=$_POST['buyer_id'];
    $order_id=$_POST['order_id'];
	$order_number=$_POST['order_number'];
    $status=$_POST['status'];
	$date_added=$_POST['date_added'];
	$head=makeTableName((string)$id);
	$tablename=$head.'_orders';
    //get current date
    $today= date("d-m-Y H:i");
	if($status==0)
	{
		//delete the order
		$sql_command_delete_row ="DELETE FROM $tablename WHERE ordernumber = '$order_number' && userid='$buyer_id' && dateadded='$date_added' ";
		$result_delete_row=mysqli_query($db::$connection,$sql_command_delete_row) ;
		if(!$result_delete_row)
		{
			$response["success"] = -1;
			$response["message"] = "error deleting order.". mysqli_error($db::$connection);
			echo json_encode($response);
		}
		//clear the order in buyers_account
		$sql_command_orders="SELECT *FROM buyers_accounts WHERE id = '$buyer_id'";
		$result_orders = mysqli_query($db::$connection,$sql_command_orders) ;
		$result_orders = mysqli_fetch_array($result_orders);
		$orders=$result_orders['orders'];
		//the order is in the form $orders.';'.$seller_id.','.$order_number.','.$date_added;
		$new_orders="";
		$order_to_look=$id.','.$order_number.','.$date_added;
		$count_orders_added=0;
		$orders_pieces=explode(";",$orders);
		for($c=0; $c<count($orders_pieces); $c++)
		{
			if(!($orders_pieces[$c]==$order_to_look))
			{
				if($count_orders_added>0)
					$new_orders.=';';
				$new_orders.=$orders_pieces[$c];
				$count_orders_added+=1;
			}
		}
		//update the orders 
		$sql_command_insert_order="UPDATE buyers_accounts SET orders ='$new_orders', datechanged = '$today' WHERE id = '$buyer_id'";
		$result_insert_order = mysqli_query($db::$connection,$sql_command_insert_order) ;
		if(!$result_insert_order)
		{
			$response["success"] = -1;
			$response["message"] = "error inserting order.". mysqli_error($db::$connection);
			echo json_encode($response);
		}
		
	}
	else
	{
		// mysql updating a new row
		$sql_command_update="UPDATE $tablename SET orderstatus='$status', datechanged='$today' WHERE ordernumber = '$order_number' && userid='$buyer_id' && dateadded='$date_added'";
		$result_update = mysqli_query($db::$connection,$sql_command_update) ; 
		if(!$result_update)
		{			
			$response["success"] = -2;
			$response["message"] = "error updating order.". mysqli_error($db::$connection);
			echo json_encode($response);

		}
	}
	//successful
	$response["success"] = 1;
	//$response["data"] = ":".$order_to_look;
    $response["message"] = "updated.";
    echo json_encode($response);
}
else 
    {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
     echo json_encode($response);
    }
?>