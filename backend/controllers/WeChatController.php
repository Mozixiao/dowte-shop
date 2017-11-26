<?php

namespace backend\controllers;

use \Yii;
use common\base\BaseEndController;
use common\sdk\ZhiXin;
use common\util\Http;

class WeChatController extends BaseEndController
{
    public $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
                </xml>";

    public $fromUsername;
    public $toUsername;
    public $time;

    public function beforeAction($action)
    {
        if (isset($_GET["echostr"])) {
            if ($this->checkSignature()) {
                echo $_GET["echostr"];
                exit;
            }
        } else {
           return $this->responseMsg();
        }
    }

    public $accessTokenParam = '?access_token=';

    public function actionIndex()
    {
        //valid signature , option

    }

    public function actionMenuList()
    {
        $url = Yii::$app->params['weChatUrl']['menu']['list'];

        $menuJson = Http::get($url . $this->accessTokenParam);
        return $this->render('menu-list', ['menus' => $menuJson]);
    }

    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = file_get_contents('php://input');
        //extract post data
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); #这里有从用户通过公众平台接收过来的数据，具体是什么类型的数据，开发者文档上写的很清楚，可以去上面查。
            $this->fromUsername = $postObj->FromUserName;
            $this->toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $msgType = $postObj->MsgType;
            $this->time = time();
            switch( $msgType ){
                case "text": #这个xml格式的数据是你服务器上的数据，是要传回公众平台的。我在这刚开始有点糊涂了
                    $this->keyWordsSwitch($keyword);
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
                        $contentStr = "欢迎关注qianmiansen           支持查询天气如输入(北京天气)";

                        $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
                        echo $resultStr;
                        break;
                    }
            }

        }else {
            echo "欢迎关注qianmiansen";
            exit;
        }
    }

    private function keyWordsSwitch($keyWord)
    {
        $keywordArr = require_once(__DIR__ . '/../../common/config/wechat-keywords.php');
        $mapKeyWords = $this->matchKeys($keyWord);
        $keyValue = isset($keywordArr['keywords'][$mapKeyWords]) ? $keywordArr['keywords'][$mapKeyWords] : 0;
        $msgType = 'text';
        switch ($keyValue) {
            case '1' :
                $contentStr = date("Y-m-d H:i:s",time());
                $resultStr = sprintf($this->textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
                echo $resultStr;
                break;
            case '2' :
                $ZhiXin = new ZhiXin(\Yii::$app->params['ZhiXin']['key'], \Yii::$app->params['ZhiXin']['uid']);
                $city = str_replace('天气', '', $keyWord);
                $weather = json_decode($ZhiXin->getWeather($city),true);
                $daily = $weather['results'][0]['daily'][0];
                $daily['precip'] = empty($daily['precip']) ? 0 : $daily['precip'];
                $content = "今日:{$daily['date']}                       白天:{$daily['text_day']}, 晚上:{$daily['text_night']}                       最高气温:{$daily['high']}℃, 最低气温:{$daily['low']}℃  降雨率:{$daily['precip']}%, 风力:{$daily['wind_scale']}级";
                $resultStr = sprintf($this->textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $content);
                echo $resultStr;
                break;
            default :
                exit;
        }
    }

    private function matchKeys($keyWords)
    {
        if (strpos($keyWords, '天气') !== false) {
            return '天气';
        }
        return $keyWords;
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