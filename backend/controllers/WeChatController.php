<?php

namespace backend\controllers;

use \Yii;
use common\base\BaseEndController;
use common\sdk\HeFeng;
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
                $HeFeng = new HeFeng(\Yii::$app->params['HeFeng']['key']);
                $city = str_replace('天气', '', $keyWord);
                if (empty($city)) {
                    echo $this->returnText('请提供你要查询天气的城市名称，如北京天气');
                    exit;
                }
                $weather = json_decode($HeFeng->getNowWeather($city), true);
                $threeWeather = json_decode($HeFeng->getThreeWeather($city), true);
                if (!isset($weather['HeWeather6'][0]) || !isset($threeWeather['HeWeather6'][0])) {
                    echo $this->returnText('哎呀，没有查到你要查询城市的天气');
                    exit;
                }
                $daily = $weather['HeWeather6'][0];
                $threeDay = $threeWeather['HeWeather6'][0];
                $firstCond = $threeDay['daily_forecast'][0]['cond_txt_d'] == $threeDay['daily_forecast'][0]['cond_txt_n'] ? $threeDay['daily_forecast'][0]['cond_txt_d'] : "白天: {$threeDay['daily_forecast'][0]['cond_txt_d']}, 夜间: {$threeDay['daily_forecast'][0]['cond_txt_n']}";
                $secondCond = $threeDay['daily_forecast'][1]['cond_txt_d'] == $threeDay['daily_forecast'][1]['cond_txt_n'] ? $threeDay['daily_forecast'][1]['cond_txt_d'] : "白天: {$threeDay['daily_forecast'][1]['cond_txt_d']}, 夜间: {$threeDay['daily_forecast'][1]['cond_txt_n']}";
                $thirdCond = $threeDay['daily_forecast'][2]['cond_txt_d'] == $threeDay['daily_forecast'][2]['cond_txt_n'] ? $threeDay['daily_forecast'][2]['cond_txt_d'] : "白天: {$threeDay['daily_forecast'][2]['cond_txt_d']}, 夜间: {$threeDay['daily_forecast'][2]['cond_txt_n']}";
                $firstWind = preg_match('/\d/', $threeDay['daily_forecast'][0]['wind_sc']) ? "风力{$threeDay['daily_forecast'][0]['wind_sc']}级" : $threeDay['daily_forecast'][0]['wind_sc'];
                $secondWind = preg_match('/\d/', $threeDay['daily_forecast'][1]['wind_sc']) ? "风力{$threeDay['daily_forecast'][1]['wind_sc']}级" : $threeDay['daily_forecast'][1]['wind_sc'];
                $thirdWind = preg_match('/\d/', $threeDay['daily_forecast'][2]['wind_sc']) ? "风力{$threeDay['daily_forecast'][2]['wind_sc']}级" : $threeDay['daily_forecast'][2]['wind_sc'];

                $contentArr = [
                    "实况: {$daily['now']['cond_txt']} 气温: {$daily['now']['tmp']}℃, 降水量: {$daily['now']['pcpn']}",
                    "今天: $firstCond 气温: {$threeDay['daily_forecast'][0]['tmp_min']}℃~{$threeDay['daily_forecast'][0]['tmp_max']}℃, {$firstWind}, 降水率: {$threeDay['daily_forecast'][0]['pop']}%",
                    "明天: $secondCond 气温: {$threeDay['daily_forecast'][1]['tmp_min']}℃~{$threeDay['daily_forecast'][1]['tmp_max']}℃, {$secondWind}, 降水率: {$threeDay['daily_forecast'][1]['pop']}%",
                    "后天: $thirdCond 温度: {$threeDay['daily_forecast'][2]['tmp_min']}℃~{$threeDay['daily_forecast'][2]['tmp_max']}℃, {$thirdWind}, 降水率: {$threeDay['daily_forecast'][2]['pop']}%",
                ];

                $resultStr = $this->returnText(implode("\n", $contentArr));
                echo $resultStr;
                break;
            default :
                echo '';
                exit;
        }
    }

    private function returnText($content)
    {
        return sprintf($this->textTpl, $this->fromUsername, $this->toUsername, $this->time, 'text', $content);
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