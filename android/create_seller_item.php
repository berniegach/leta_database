<?php
/*
 * Following code will create a new seller account
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
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['category_id']) && isset($_POST['group_id']) )
    {
		//store the posted values in variables
		$id = $_POST['id'];
		$name = $_POST['name'];
		$category_id=$_POST['category_id'];
		$group_id=$_POST['group_id'];
		
		//get current date
		$today= date("d-m-Y H:i");
		//get the table name
		$head=makeTableName((string)$id);
		$tablename=$head.'_items';
		 		
		//check if the email is already registered
		//NOTE the tablename is not in quotes and the column is in quotes coz...
		$sql_command_check="SELECT *FROM $tablename WHERE categories = '$category_id' AND groups = '$group_id' AND items = '$name'";
		$result_check = mysqli_query($db::$connection,$sql_command_check) ;
		if(!$result_check || mysqli_num_rows($result_check)>0)
		{
			// failed to insert column
			$response["success"] = -2;
			$response["message"] = "item already there.". mysqli_error($db::$connection);
			echo json_encode($response);
		}
		else
		{
			// mysql inserting a new row
			$sql_command="INSERT INTO $tablename(categories,groups,items, sellingprice, available, dateadded) VALUES('$category_id','$group_id','$name','0.0','1','$today')";
			$result = mysqli_query($db::$connection,$sql_command) ;
			if(!$result)
			{
				// failed to insert row
				$response["success"] = 0;
				$response["message"] = "Oops! An error occurred.". mysqli_error($db::$connection);
				// echoing JSON response
				echo json_encode($response);
			}
			else
			{
				//sucess
				$last_id= mysqli_insert_id($db::$connection);
				$response["success"] = 1;
				$response["id"] = (string)$last_id;
				$response["dateadded"] = $today;
				// echoing JSON response
				echo json_encode($response);
			}
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
?>