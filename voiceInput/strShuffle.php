<?php
header("Content-type:text/html; charset=UTF-8");
echo strShuffle("多看看打开的");
function strShuffle($tempaddtext)
{
    $tempaddtext = iconv("UTF-8", "gb2312", $tempaddtext);
    $cind = 0;
    $arr_cont=array();

    for($i=0;$i<strlen($tempaddtext);$i++)
    {
        if(strlen(substr($tempaddtext,$cind,1)) > 0){
            if(ord(substr($tempaddtext,$cind,1)) < 0xA1 ){ //如果为英文则取1个字节
                array_push($arr_cont,substr($tempaddtext,$cind,1));
                $cind++;
            }else{
                array_push($arr_cont,substr($tempaddtext,$cind,2));
                $cind+=2;
            }
        }
    }
    foreach ($arr_cont as &$row)
    {
        $row=iconv("gb2312","UTF-8",$row);
    }
    shuffle($arr_cont);
    $transferred_str = implode("", $arr_cont);
    return $transferred_str;
}
