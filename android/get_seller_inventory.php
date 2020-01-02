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
if (isset($_POST["id"]))
    {
    $id=$_POST['id'];
    $tablename=makeTableName((string)$id);
    $tablename='inv'.$tablename;
    // get the inventory
    $sql_command="SELECT *FROM $tablename";
    $result = mysqli_query($db::$connection,$sql_command) ;
    if (!empty($result))
        {
        // check for empty result
        if (mysqli_num_rows($result) > 0) 
            {
            $response["inventory"]=array();
             while ($row= mysqli_fetch_array($result))
            {
                //temp certificates row
                  $inventory=array();
                  $inventory['id']=$row['id'];
                  $inventory['category']=$row['category'];
                  $inventory['groups']=$row['groups'];
                  $inventory['serialized']=$row['serialized'];
                  $inventory['item']=$row['item'];
                  $inventory['description']=$row['description'];
                  $inventory['sizes']=$row['sizes'];
                  $inventory['buying_price']=$row['buying_price'];
                  $inventory['selling_price']=$row['selling_price'];
                  $inventory['locked']=$row['locked'];
                  $inventory['date_added']=$row['date_added'];
                  $inventory['date_changed']=$row['date_changed'];
                  //push a single schema into array
                   array_push($response["inventory"], $inventory); 
                
            }
            //response
            $response["success"] = 1;
            $response["message"] = "found them";
            echo json_encode($response);
           
            }
            else
                {
                }
    }
    else 
        {
        // no account
        $response["success"] = 0;
        $response["message"] = "no inventory". mysqli_error($db::$connection);
        echo json_encode($response);
        }
}
else
    {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing". mysqli_error($db::$connection);
    echo json_encode($response);
    }
?>