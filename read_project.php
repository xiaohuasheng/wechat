<?php
$name_file = "./project.txt";
$file = file($name_file);
foreach($file as &$line) 
{
    echo $line.'<br/>';
}
