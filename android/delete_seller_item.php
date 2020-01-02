<?php
 
/*
 * Following code will delete a buyer from the system.
 * the first is to remove the folders belonging to the user. path is 'http://'.$server_ip.'/leta_project/android/res' . folder name is user's id
 * third is to remove the user's notifications  table 
 * lastly remove the user's info row in the buyers account table
 * All product details are read from HTTP Post Request
 */
 // array for JSON response
$response = array();
$response["message"]="";
//creating upload url
$delete_url=$_SERVER['DOCUMENT_ROOT'].'/leta_project/android/src/sellers/';
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db = new DB_CONNECT();

// check for required fields
if (isset($_POST['id']) && isset($_POST['item_id']))
    {
		//store the posted values in variables
		$id = $_POST['id'];
		$item_id = $_POST['item_id'];
		
		//get current date
		$today= date("d-m-Y H:i");
		//get the table name
		$head=makeTableName((string)$id);
		$tablename=$head.'_items';
		
		//delete the category's row
		$sql_command_delete_row ="DELETE FROM $tablename WHERE id = $id";
		$result_delete_row=mysqli_query($db::$connection,$sql_command_delete_row) ;
		if(!$result_delete_row)
		{
			$response["success"] = 0;
			$response["message"] = "error deleting group  info.". mysqli_error($db::$connection);
			echo json_encode($response);

		}
		//delete the group pic
		$delete_url=$delete_url.$head.'/pics/i_'.$item_id.'.jpg';
		if(file_exists($delete_url))
			unlink($delete_url);
		$response["success"] = 1;
		echo json_encode($response);
			
	} 
		

 else
	 {
    // required field is missing
    $response["success"] = -1;
    $response["message"] = "Required field(s) is missing";
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