<?php

$lat = $_GET['lat'];
$lng = $_GET['lng'];
$distance = $_GET['distance'];


$conn = new PDO('pgsql:host=localhost;dbname=ldd','postgres','pg1234');

// $sql = $conn->query("insert into pointmap (geom, tname) values (ST_SetSRID(ST_Point(" . $lng . "," . $lat ."), 4326),'" . $name . "');");

$result = $conn->query("select l.geom , ST_AsGeoJSON(geom,5) AS geojson from th_landmark l where st_distancesphere(st_geomfromtext('POINT($lng $lat)',4326),l.geom) < $distance");

// $result  = $conn->query("SELECT * , ST_AsGeoJSON(geom,5) AS geojson FROM public.th_landmark ORDER BY id ASC LIMIT 100");
// echo var_dump($result); 
// echo $result->rowCount();
$features=[];
foreach ($result AS $row){
    unset($row['geom']);
    $geometry = $row['geojson']=json_decode($row['geojson']);
    unset($row['geojson']);
    $feature = ["type"=>"Feature","geometry"=>$geometry,"properties"=>$row];
    // echo json_encode($feature)."<br><br>";
    array_push($features,$feature);
}
$featureCollection = ["type"=>"FeatureCollection","features"=>$features];
echo json_encode($featureCollection);


// $rs = $conn->query($sql);
// if (!$rs) {
//     echo 'An SQL error occured.\n';
//     exit;
// }

// header('Content-type: application/json');
// echo "Done";
// $conn = NULL;

?>