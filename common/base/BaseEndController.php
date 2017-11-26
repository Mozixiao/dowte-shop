<?php

namespace common\base;


use yii\helpers\Json;
use yii\web\Controller;

class BaseEndController extends Controller
{
    public function renderJson($data)
    {
        print(Json::encode($data));
    }

    public function renderMson($params = [])
    {
        $ret = [];
        $ret['success'] = true;
        $ret['result'] = $params;
        $ret['errors'] = [];
        return $this->renderJson($ret);
    }
}