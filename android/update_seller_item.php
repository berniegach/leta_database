<?php
 
/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 // array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db = new DB_CONNECT();

// check for required fields
if (isset($_POST['id']) && isset($_POST['category_id']) && isset($_POST['group_id']) && isset($_POST['item_id']) && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['selling_price']) && isset($_POST['available']) )
    {
		//store the posted values in variables
		$id = $_POST['id'];
		$category_id = $_POST['category_id'];
		$group_id = $_POST['group_id'];
		$item_id = $_POST['item_id'];
		$item = $_POST['name'];
		$description=$_POST['description'];
		$selling_price=$_POST['selling_price'];
		$available=$_POST['available'];
		//get current date
		$today= date("d-m-Y H:i");
		//get the table name
		$head=makeTableName((string)$id);
		$tablename=$head.'_items';
		//check if the name is already there
		//NOTE the tablename is not in quotes and the column is in quotes coz...
		$sql_command_check="SELECT *FROM $tablename WHERE categories = '$category_id' AND groups = '$group_id' AND items = '$item'";
		$result_check = mysqli_query($db::$connection,$sql_command_check) ;
		if(!$result_check || mysqli_num_rows($result_check)>0)
		{
			//category name already there so we ignore
		}
		else
		{
			//the category name is new so we update
			$sqlcommand_update_name="UPDATE $tablename SET items='$item' WHERE id='$item_id'";
			$result_update_name = mysqli_query($db::$connection,$sqlcommand_update_name) ;
			if(!$result_update_name)
			{
				// failed to insert column
				$response["success"] = -1;
				$response["message"] = " failed to update item name ". mysqli_error($db::$connection);
				echo json_encode($response);
			}
			
		}
		//update the description
		$sqlcommand_update_des="UPDATE $tablename SET descriptions='$description' , sellingprice = '$selling_price', available='$available', datechanged='$today' WHERE id='$item_id'";
		$result_update_des = mysqli_query($db::$connection,$sqlcommand_update_des) ;
		if(!$result_update_des)
		{
			// failed to insert column
			$response["success"] = -2;
			$response["message"] = "failed to update item description ". mysqli_error($db::$connection);
			echo json_encode($response);
		}
		$response["success"] = 1;
		$response["date_changed"] = $today;
		echo json_encode($response);	
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