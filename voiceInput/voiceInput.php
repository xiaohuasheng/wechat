<?php
header('Content-type:text');

define("TOKEN", "xiaohuasheng");
$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }

    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R \r\n".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T \r\n".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收文本消息
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        $content = $this->strShuffle($keyword);
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            //$content = strShuffle($object->Recognition);
            $textContent = $object->Recognition;
            $ZH_content= $this->deleteNotZh($textContent);
            $content = $this->strShuffle($ZH_content);
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        if (!isset($content) || empty($content)){
            return "";
        }

        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }


    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
        <MediaId><![CDATA[%s]]></MediaId>
        </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[voice]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    private function deleteNotZh($str)
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

    //把文字乱排
    private function strShuffle($tempaddtext)
    {
        //记录日志信息
        //$this->logger($tempaddtext); 
        $log_content = $tempaddtext;
        $log_file= fopen("log.txt", "a");
        fwrite($log_file, date('Y-m-d H:i:s')." ".$log_content."\r\n");
        fclose($log_file);

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
        //记录日志信息
        //$this->logger($transferred_str); 
        $log_content = $transferred_str;
        $log_file= fopen("log.txt", "a");
        fwrite($log_file, date('Y-m-d H:i:s')." ".$log_content."\r\n");
        fclose($log_file);
        return $transferred_str;
    }

    //日志记录
    private function logger($log_content)
    {
        $log_file= fopen("log.txt", "a");
        fwrite($log_file, date('Y-m-d H:i:s')." ".$log_content."\r\n");
        fclose($log_file);
    }
}
?>
