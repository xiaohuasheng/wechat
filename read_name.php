<?php
$name_file = "./name.txt";
$file = file($name_file);
foreach($file as &$line) 
{
    echo $line.'<br/>';
}
