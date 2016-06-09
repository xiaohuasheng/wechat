<?php
$nameFile = @fopen("./name.txt", "r");
$projectFile= @fopen("./project.txt", "r");
$nameProjectXml = @fopen("./name_project.xml", "w");
if ($nameFile) {
    while (!feof($nameFile)) {
        //$name = trim(fgets($nameFile));
        $name = trim(str_replace(' ','',fgets($nameFile)));
        $project = trim(fgets($projectFile));
        $str = "<name>".$name."</name>";
        fwrite($nameProjectXml, $str);
        fwrite($nameProjectXml, "\r\n");
        $str = "<project>".$project."</project>";
        fwrite($nameProjectXml, $str);
        fwrite($nameProjectXml, "\r\n");
    }
    fclose($nameFile);
    fclose($projectFile);
}
?>
