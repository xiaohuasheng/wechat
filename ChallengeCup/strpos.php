<?php
header("Content-type:text/html; charset=UTF-8");
$keyword = "2016挑战杯曾华生";
$keywordChallengeCup = substr($keyword, 0, 12);
$keyword_name = substr($keyword, 13);
if($keywordChallengeCup == "2016挑战杯")
{
    echo $keywordChallengeCup;
    echo $keyword_name;
}
else
{
    echo "none";
}
