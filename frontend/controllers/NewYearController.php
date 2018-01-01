<?php
namespace frontend\controllers;

use common\base\BaseEndController;

class NewYearController extends BaseEndController
{
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionIndex($name = '')
    {
        $param = $this->getWishes($name);
        return $this->render('index', $param);
    }

    private function getWishes($name)
    {
        $name = strtoupper($name);
        $wishes = require_once __DIR__ . '/../config/wishes.php';
        if (empty($name)) {
            $param = [];

        } else {
            if (isset($wishes['value'][$name])) {
                $param = $wishes['value'][$name];
            } else {
                if (isset($wishes['key'][$name]) && isset($wishes['value'][$wishes['key'][$name]])) {
                    $param = $wishes['value'][$wishes['key'][$name]];
                } else {
                    $param = $wishes['default'];
                }
            }
        }
        return $param;
    }
}