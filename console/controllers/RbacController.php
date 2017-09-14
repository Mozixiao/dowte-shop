<?php

namespace console\controllers;

use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * Init base roles
     */
    public function actionInit() {

        $auth = \Yii::$app->authManager;

        $auth->removeAll();

        $managerUser = $auth->createPermission("managerUser");
        $managerUser->description = "manage user list";
        $auth->add($managerUser);

        $guest = $auth->createRole("guest");
        $auth->add($guest);

        $admin = $auth->createRole("admin");
        $auth->add($admin);
        $auth->addChild($admin, $managerUser);
    }

    /**
     * Assign a specific role to the given user id
     * @param int $userId
     * @param string $role
     * @throws \Exception
     */
    public function actionAssign($userId, $role) {
        $auth = \Yii::$app->authManager;
        $roleItem = $auth->getRole($role);
        If ($roleItem == null) {
            throw new \Exception("the role is not found");
        }
        $auth->assign($roleItem, $userId);
    }
}