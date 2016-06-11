<?php
header("Content-type:text/html;charset=UTF-8");
$mysqli = new mysqli('114.215.111.84', 'root', '123456', 'wechat');
$sql = "select * from ChallengeCup";
$result = $mysqli -> query($sql);

$xmlStr = "<?xml version=1.0 charset=UTF-8>";
$xmlStr .= "<ChallengeCup>";
while($row = $result -> fetch_assoc())
{
    $xmlStr .= "<project>";
        $xmlStr .= "<id>".$row['id']."</id>";
        $xmlStr .= "<name>".$row['name']."</name>";
        $xmlStr .= "<theme>".$row['theme']."</theme>";
        $xmlStr .= "<level>".$row['level']."</level>";
    $xmlStr .= "</project>";
}
$xmlStr .= "</ChallengeCup>";

$fp = fopen("xmlData", "w");
fwrite($fp, $xmlStr);
fclose($fp);
