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
/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula. This formula is stable for small distances
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
  function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}
/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
 function vincentyGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}
// check for post data
if (isset($_POST["latitude"]) && isset($_POST["longitude"]) && isset($_POST["location"]) && isset($_POST["which"]))
    {
	//$country=$_POST["country"]
	$latitude=$_POST["latitude"];
	$longitude=$_POST["longitude"];
	$location=$_POST["location"];
	$which=$_POST["which"];
	$restaurants=array();
	//loop through the sellers accounts filtering using COUNTRY,LOCATION AND LAST LATITUDE AND LONGITUDE , we return restaurants within 20ms starting with the nearest
	$sql_command="SELECT *FROM sellers_accounts";
    $result = mysqli_query($db::$connection,$sql_command) ;
	if(!empty($result) && mysqli_num_rows($result)>0)
	{
		while($row= mysqli_fetch_array($result))
		{
			//$seller_country=$result['country'];
			$seller_location=$row['location'];
			$location_pieces= explode(",", $seller_location);//[0] is latitude [1] is longitude [2] is local location
			//first check the country
			//if($country != $seller_country)
				//continue;
			if(count($location_pieces)<2)
				continue;
			//check the local location
			if($location_pieces[2] != $location)
				continue;
			//now calculate the distance
			$distance=haversineGreatCircleDistance($latitude, $longitude, $location_pieces[0], $location_pieces[1], 6371000);
			//filter those restaurants more than 50m away
			if($which==1)
				$check_distance=50;
			else
				$check_distance=10000;
			if($distance>$check_distance)
				continue;
			$info=array();
			$info['id']=$row['id'];
			$info['username']=$row['usernames'];
			$info['distance']=$distance;
			$info['latitude']=$location_pieces[0];
			$info['longitude']=$location_pieces[1];
			$info['locality']=$location_pieces[2];
			$info['country']=$row['country'];
			$info['order_radius']=$row['orderrange'];
			$info['number_of_tables']=$row['numberoftables'];
			array_push($restaurants,$info);
			
		}
		$response["success"] = 1;
		$response["restaurants"] = array();
		array_push($response["restaurants"], $restaurants); 
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