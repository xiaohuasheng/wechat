<?php

define("TOKEN", "ChallengeCup");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            
            //先判断是不是查询挑战杯消息的
            $keywordChallengeCup = substr($keyword, 0, 13);
            if($keywordChallengeCup == "2016挑战杯")
            {
                //1、读取xml数据文档，创建xml对象。
                $xml = simplexml_load_file("./ChanllengeCup_name_theme_level.xml");
                //2、从“2016挑战杯xxx”中取出名字
                $keyword_name = substr($keyword, 13);
                //3、搜索名字是否在数据中
                //5、设置是否有名字在数据中的标志 inFlag，初始值为false
                $inFlag = false;
                for($j=1; $j<=226; $j++)
                {
                    $name = $xml->xpath("project[$j]/name");
                    if($keyword_name == $name[0])
                    {
                        //4、有名字的话输出主题theme的信息。
                        $inFlag = true;
                        $theme = $xml->xpath("project[$j]/theme");
                        //$level = $xml->xpath("project[$j]/level");
                        $msgType = "text";
                        //$contentStr = $project[$j][0];
                        $contentStr = "恭喜你！您的项目获批立项，获批项目名称:".$theme[0]."，具体获批项目等级与详情以红网通知为准。";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }
                }

                //如果没有立项成功，返回相应的结果
                if(!$inFlag)
                {
                    $msgType = "text";
                    //$contentStr = $project[$j][0];
                    $contentStr = "很遗憾！您的项目立项没有成功。";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }
            }
            else
            {
                $msgType = "text";
                //$contentStr = $project[$j][0];
                $contentStr = "未知消息，如果您想查询挑战杯立项情况，请输入：2016挑战杯姓名";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
        }else{
            echo "";
            exit;
        }
    }
}
?>
