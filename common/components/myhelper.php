<?php

namespace common\components;

use Yii;
use yii\base\Component;

class myhelper extends Component {

  public function to_date($p_date) {

    return \Yii::$app->formatter->asDate($p_date,'php:Y-m-d');

  }
}
