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
		$tablename=$head.'_items';
		$sql_command="SELECT *FROM $tablename";
		$result = mysqli_query($db::$connection,$sql_command) ;     
		if(!empty($result) )
		{
			$response["items"]=array();
			while($row= mysqli_fetch_array($result))
			{
				$data=array();
				$data["id"]=$row['id'];
				$data["category"]=$row['categories'];
				$data["group"]=$row['groups'];
				$data["item"]=$row['items'];
				$data["sellingprice"]=$row['sellingprice'];
				$data["description"]=$row['descriptions'];
				$data["available"]=$row['available'];
				$data["dateadded"]=$row['dateadded'];
				$data["datechanged"]=$row['datechanged'];
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