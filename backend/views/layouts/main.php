<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<span class="glyphicon glyphicon-th-large"></span> Обращения граждан',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-default navbar-fixed-top',
        ],
    ]);
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход в систему', 'url' => ['/site/login']];
    } else {
                
        $menuItems = [
                    [
                        'label' => '<span class="glyphicon glyphicon-list-alt"></span> Обращения', 
                        'url' => ['/site/index'],
                        'active' => in_array(
                                    $this->context->route, 
                                        ['site/index','site/form']),
                    ],
        ];
        
        if (Yii::$app->user->identity->isTfomsRole( Yii::$app->user->identity->id )) {
            
            $menuItems[] = [
                            'label' => '<span class="glyphicon glyphicon-cog"></span> Настройки',
                            'url' => ['/settings/index'],
                            'active' => in_array(
                                    $this->context->route, 
                                        ['settings/index','settings/reasons','settings/commons','settings/company',
                                         'settings/user-form','settings/reason-form','settings/common-form','settings/company-form']),
                        ];
        }
        
        $menuItems[] = [
                        'label' => '<span class="glyphicon glyphicon-user"></span> Выйти ('.Yii::$app->user->identity->user_name.')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post'],
                    ];
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ТФОМС Курганской области <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
