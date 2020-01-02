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
// include db connect class
require_once __DIR__ . '/db_connect.php';
// connecting to db
$db = new DB_CONNECT();

// check for required fields
if (isset($_POST['id']) )
    {
		//store the posted values in variables
		$id = $_POST['id'];
		
		//get current date
		$today= date("d-m-Y H:i");
		//get the table name
		$head=makeTableName((string)$id);
		
		//user's own folder
		$folder='src/buyers/'.$head.'/';
		//delete the folder
		deleteDir($folder);
		
		//delete the users notifications and tasks tables
		$notifications=$head.'_b_notifications';
		$sql_command_delete_note="DROP TABLE $notifications";
		$result_delete_note=mysqli_query($db::$connection,$sql_command_delete_note) ;
		if(!($result_delete_note ))
		{
			$response["message"] .= "error deleting note table.". mysqli_error($db::$connection);
		}
		//delete users info from users account table
		$sql_command_delete_row5 ="DELETE FROM buyers_accounts WHERE id = $id";
		$result_delete_row5=mysqli_query($db::$connection,$sql_command_delete_row5) ;
		if(!$result_delete_row5)
		{
			$response["message"] .= "error deleting users account info.". mysqli_error($db::$connection);
		}
		
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
	//delete the directory and its contents
	function deleteDir($dirPath)
	{
		
		if(!is_dir($dirPath))
		{
			//$response["message"].="no directory ".$dirPath;  //no directory
			return;
		}
		//if the last character is not a slash, add it for reasons
		if(substr($dirPath,strlen($dirPath)-1,1) != '/')
		{
			$dirPath.='/';
		}
		//search for all the path names of the folder contents. GLOB_MARK means attach slash to the end of the path name. Return as array
		$files=glob($dirPath.'*',GLOB_MARK);
		foreach($files as $file)
		{
			//loop through the file paths and if its is a folder . carry out the outlayed procedure
			if(is_dir($file))
			{
				deleteDir($file);
			}
			else
			{
				//delete the file
				unlink($file);
			}
		}
		//delete the folder
		rmdir($dirPath);
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