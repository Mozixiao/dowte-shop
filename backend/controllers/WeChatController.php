<?php

namespace backend\controllers;

use common\base\BaseEndController;

class WeChatController extends BaseEndController
{
    public function actionValid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }


    public function actionResponseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];


        //extract post data
        if (!empty($postStr)){

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); #这里有从用户通过公众平台接收过来的数据，具体是什么类型的数据，开发者文档上写的很清楚，可以去上面查。
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $msgType = $postObj->MsgType;
            $time = time();
            switch( $msgType ){
                case "text": #这个xml格式的数据是你服务器上的数据，是要传回公众平台的。我在这刚开始有点糊涂了
                    $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>

                </xml>";

                    #这里是我自己写的，关于时间的自动回复
                    if( $keyword =='时间' || $keyword =='time' || $keyword =="shijian"){
                        $contentStr = date("Y-m-d H:i:s",time());
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }
                    else
                    {
                        $msgType = "text";
                        $contentStr = "欢迎关注qianmiansen";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }

                    break;

                case "event": #这个是事件的操作，当关注的时候自动回复
                    $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
                </xml>";
                    $event = $postObj->Event;
                    $msgType = "text";
                    if( $event =='subscribe'){
                        $contentStr = "欢迎关注qianmiansen";

                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                        break;
                    }
            }

        }else {
            echo "欢迎关注qianmiansen";
            exit;
        }
    }
///----------------------------------------------------------------------------------
    private function checkSignature() #这个函数验证过之后就可以删除了
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'qianmiansen';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}