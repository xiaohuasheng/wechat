<?php
//header('Content-type:text');
echo deleteNotZh("打开看的kdkd./大口大口");
function deleteNotZh($str)
{
    //因为输入的$str就是utf-8编码，所以不用转换了
    //转换 GB2312 -> UTF-8
    //$str = mb_convert_encoding($str, 'UTF-8', 'GB2312');
     
    preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches);
    $str = join('', $matches[0]);
     
    //转换 UTF-8 -> GB2312
    //$str = mb_convert_encoding($str, 'GB2312', 'UTF-8'); 
    return $str;
}
