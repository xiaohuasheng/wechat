<?php
header("Content-type:text/html;charset=utf-8");
$input = "张晓燕";
//$input = "邓智宇";
/*
 * 想先读取全部的名字，在循环里读取到名字后就选那一个Project对应的主题和等级
 * 
 * */
$xml = simplexml_load_file("./ChanllengeCup_name_theme_level.xml");
echo '<table align="center" border="1">';
for($j=1; $j<=226; $j++)
{
    echo '<tr>';
        $name = $xml->xpath("project[$j]/name");
        $theme = $xml->xpath("project[$j]/theme");
        $level = $xml->xpath("project[$j]/level");
        echo '<td>'.$j.'</td>';
        echo '<td>'.$name[0].'</td>';
        echo '<td>'.$theme[0].'</td>';
        echo '<td>'.$level[0].'</td>';
    echo '</tr>';
}
echo '</table>';

class Project
{
    private $name;
    private $theme;
    private $level;

    function __construct($name, $theme, $level)
    {
        
    }
}
?>
