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
//getting server ip
$server_ip= gethostbyname(gethostname());
//creating upload url
$mkdir_url='http://'.$server_ip.'/leta_project/android/res';

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
if (isset($_POST['email']) && isset($_POST['password']))
    {
		//store the posted values in variables
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		//get current date
		$today= date("d-m-Y H:i");
		
		//hash the password
		$hashed_password= password_hash($password, PASSWORD_DEFAULT);
		 		
		//check if the email is already registered
		$sql_command_check="SELECT *FROM buyers_accounts WHERE emails = '$email'";
		$result_check = mysqli_query($db::$connection,$sql_command_check) ;
		if(!$result_check || mysqli_num_rows($result_check)>0)
			{
				// failed to insert row
				$response["success"] = -1;
				$response["message"] = "email already there.". mysqli_error($db::$connection);
				echo json_encode($response); 
			}
			else
			{
				// mysql inserting a new row
		$sqlcommand="INSERT INTO buyers_accounts(emails, passwords, dateadded)"
            . " VALUES('$email','$hashed_password','$today')";
		$result = mysqli_query($db::$connection,$sqlcommand) ; 
 
		// check if row inserted or not 
		if ($result)
        {
			// successfully inserted into database
            $last_id= mysqli_insert_id($db::$connection);
			
			//get the various table names
            $head=makeTableName((string)$last_id);
						
			//make the table names
			$notifications=$head.'_b_notifications';
			
			//make the commands
			$sql_command_add_note="CREATE TABLE $notifications ( id INT(11) PRIMARY KEY AUTO_INCREMENT, classes INT, messages TEXT, dateadded TEXT)";
            
			//create the tables
            $result_note = mysqli_query($db::$connection,$sql_command_add_note) ;
			
			//check if all the tables are created
            if(empty($result_note) )
            {
                $response["success"] = -2;
                $response["message"] = "Oops! An error occurred.".$head. mysqli_error($db::$connection);
				// echoing JSON response
				echo json_encode($response);
            }
			
			//create the directories
			//make dir names
			$pics='src/buyers/'.$head.'/pics';
			
			//create and check
			if(!(mkdir('src/buyers/'.$head,0777) && mkdir($pics,0777)) )
			{
				$response["success"] = -3;
                $response["message"] = "Oops! Directories not created.";
				// echoing JSON response
				echo json_encode($response);
			}
			chmod('src/buyers/'.$head,0777);
			chmod($pics,0777);
			//create the welcome notifications
			$message="Welcome to leta, please read the help document in settings for how to get started";
			$sql_command_insert="INSERT INTO $notifications (classes,messages,dateadded)"
					. " VALUES('0', '$message', '$today')";
				$result = mysqli_query($db::$connection,$sql_command_insert) ;
				if(!$result)
				{
					$response["success"] = 0;
					$response["message"] = "adding welcome notification ". mysqli_error($db::$connection);
					echo json_encode($response);
				}
			//successful 
			$response["success"] = 1;
			$response["message"] = "account successfully created.";
			echo json_encode($response);            
            }
        else 
            {
            // failed to insert row
            $response["success"] = -4;
            $response["message"] = "Oops! An error occurred.". mysqli_error($db::$connection);
            // echoing JSON response
            echo json_encode($response);
            }
				
			}
  
			
	} 
		

 else
	 {
    // required field is missing
    $response["success"] = -5;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
     echo json_encode($response);
	}
?>