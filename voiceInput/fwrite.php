<?php
    $log_content = "多看看";
    $log_file= fopen("log.txt", "a");
    fwrite($log_file, date('Y-m-d H:i:s')." ".$log_content."\r\n");
    fclose($log_file);
