<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<style>
    .skin-blue .main-header .navbar .sidebar-toggle:hover {
        background-color: #575757;
    }

</style>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">SHOP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo', 'style' => 'background-color:#303030']) ?>

    <nav class="navbar navbar-static-top" style="background-color: #303030" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>

            <div class="navbar-custom-menu" style="margin-right: 15px;">
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" data-method="post" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-question-circle-o"></i>
                        帮助
                    </a>
                </li>
                <li class="dropdown">
                    <a href="/site/logout" data-method="post" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-sign-out"></i>
                        退出
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
