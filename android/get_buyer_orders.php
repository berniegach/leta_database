<?php
/*
 * Following code will get single contractor account
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
if (isset($_POST["userid"]) &&isset($_POST["orders"]))
    {
    $user_id = $_POST['userid'];
    $orders=$_POST["orders"];
	$response["items"]=array();
	//orders are in the format seller_id,order_number:seller_id,order_number 
	$orders_pieces=explode(";",$orders);
	if(!empty($orders_pieces))
	{
		for($c=0; $c<count($orders_pieces); $c++)
		{
			$pieces=explode(',',$orders_pieces[$c]);
			if(!empty($pieces))
			{
				$seller_id=$pieces[0];
				$order_number=$pieces[1];
				$header=makeTableName((string)$seller_id);
				$table_name=$header."_orders";
				$sql_command_orders="SELECT *FROM $table_name WHERE userid = '$user_id' && ordernumber = '$order_number'";
				$result_orders = mysqli_query($db::$connection,$sql_command_orders) ;
				if( !empty($result_orders) && mysqli_num_rows($result_orders) > 0 )
				{
					while($row_orders= mysqli_fetch_array($result_orders))
					{
						$data=array();
						$data["id"]=$row_orders['id'];
						//$data["userid"]=$row_orders['userid'];
						$data["itemid"]=$row_orders['itemid'];
						$data["ordernumber"]=$row_orders['ordernumber'];
						$data["orderstatus"]=$row_orders['orderstatus'];
						$data["table_number"]=$row_orders['tablenumber'];
						$data["dateadded"]=$row_orders['dateadded'];
						$data["datechanged"]=$row_orders['datechanged'];
						//get the item names and sellingprice
						$item_id=$data["itemid"];
						$table_name_names=$header.'_items';
						$sql_command_names="SELECT *FROM $table_name_names WHERE id = '$item_id'";
						$result_names = mysqli_query($db::$connection,$sql_command_names) ; 
						if( !empty($result_names) && mysqli_num_rows($result_names) > 0 )
						{
							$row_names= mysqli_fetch_array($result_names);
							$data["item"]=$row_names['items'];
							$data["sellingprice"]=$row_names['sellingprice'];
						}
						$sql_command_format="SELECT *FROM sellers_accounts WHERE id = '$seller_id'";
						$result_format = mysqli_query($db::$connection,$sql_command_format) ; 
						if( !empty($result_format) && mysqli_num_rows($result_format) > 0 )
						{
							$row_format= mysqli_fetch_array($result_format);
							$data["order_format"]=$row_format['orderformat'];
							$data["restaurant_name"]=$row_format['usernames'];
						}
						
						//add the items into the array
						array_push($response["items"], $data);
					}
				}
			}
		}
	}
	$response["success"] = 1; 
    $response["message"] = "found them";
    echo json_encode($response);
	
}
else
    {
    // required field is missing
    $response["success"] = -3;
    $response["message"] = "Required field(s) is missing". mysqli_error($db::$connection);
    echo json_encode($response);
    }
?>