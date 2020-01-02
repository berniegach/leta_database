<?php
 
/*
 * Following code will get the boss notifications
 * The returned infos are id,  userid, classes, messages, dateadded.
 * Arguments are:
 * id==boss id.
 * Returns are:
 * success==1 successful get
 * success==0 for id argument missing
 */
 
// array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// check for required fields
if (isset($_POST['id']))
    {
		$id = $_POST['id']; 
		//get the table name
		$head=makeTableName((string)$id);
		$tablename=$head.'_orders';
		$sql_command="SELECT *FROM $tablename";
		$result = mysqli_query($db::$connection,$sql_command) ;     
		if(!empty($result) )
		{
			$response["items"]=array();
			while($row_orders= mysqli_fetch_array($result))
			{
				$data=array();
				$data["id"]=$row_orders['id'];
				$data["userid"]=$row_orders['userid'];
				$data["itemid"]=$row_orders['itemid'];
				$data["ordernumber"]=$row_orders['ordernumber'];
				$data["orderstatus"]=$row_orders['orderstatus'];
				$data["dateadded"]=$row_orders['dateadded'];
				$data["datechanged"]=$row_orders['datechanged'];
				//get the item names and sellingprice
				$item_id=$data["itemid"];
				$table_name_names=$head.'_items';
				$sql_command_names="SELECT *FROM $table_name_names WHERE id = '$item_id'";
				$result_names = mysqli_query($db::$connection,$sql_command_names) ; 
				if( !empty($result_names) && mysqli_num_rows($result_names) > 0 )
				{
					$row_names= mysqli_fetch_array($result_names);
					$data["item"]=$row_names['items'];
					$data["sellingprice"]=$row_names['sellingprice'];
				}
				//get the username
				$user_id=$data["userid"];
				$sql_command_username="SELECT *FROM buyers_accounts WHERE id = '$user_id'";
				$result_username = mysqli_query($db::$connection,$sql_command_username) ; 
				if( !empty($result_username) && mysqli_num_rows($result_username) > 0 )
				{
					$row_username= mysqli_fetch_array($result_username);
					$data["username"]=$row_username['usernames'];
				}
				$data["table_number"]=$row_orders['tablenumber'];
				array_push($response["items"], $data);
			}
			$response["success"] = 1; 
            $response["message"] = "found them";
            echo json_encode($response);
		}
		else
		{
			$response["success"] = 0; 
            $response["message"] = "no items ".mysqli_error($db::$connection);
            echo json_encode($response);
		}
	}
	else 
	{
		// required field is missing
		$response["success"] = 0;
		$response["message"] = "Required field(s) is missing";
		// echoing JSON response
		 echo json_encode($response);
	}
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


?>