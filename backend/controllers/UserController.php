<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

class UserController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className (),
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['admin']
                    ]
                ]
            ],
        ];
    }
}