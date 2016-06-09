<?php
header("Content-type:text/html;charset=utf-8");
$input = "张晓燕";
//$input = "邓智宇";
$xml = simplexml_load_file("tiaozhanbei.xml");
$name= $xml -> xpath("name");
$project = $xml->xpath("project");
$content = "";
echo '<table align="center" border="1">';
for($j=0; $j<226; $j++)
{
    echo '<tr>';
        echo '<td>'.($j+1).'</td>';
        echo '<td>'.$name[$j][0].'</td>';
        echo '<td>'.$project[$j][0].'</td>';
    echo '</tr>';
    /*
    if($input == $name[$j][0])
    {
        $content = $project[$j][0];
    }
     */
}
echo '</table>';
/*
foreach($xml->children() as $child)
{
    echo $child."<br>";
    if($input == $child)
    {
        echo $child. "<br />";
    }
}
 */
?>
