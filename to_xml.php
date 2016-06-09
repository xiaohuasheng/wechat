<?php
$name_file = file("./name.txt");
$project_file = file("./project.txt");
$name_project_xml = fopen("name_project_xml.xml", "w");
foreach($name_file as &$name)
{
    //foreach($project_file as &$project)
    {
        $str = "<name>".$name."</name>"."\n"."<project>".$project."</project>";
        fwrite($name_project_xml, $str);
    }
}
