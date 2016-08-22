<?php

namespace common\components;

use Yii;
use yii\base\Component;

class myhelper extends Component {

  public function to_date($p_date) {

    return \Yii::$app->formatter->asDate($p_date,'php:Y-m-d');

  }

  public function to_date_time($p_date) {

    return \Yii::$app->formatter->asDate($p_date,'php:Y-m-d H:i:s');

  }

  public function to_beauty_date($p_date) {

    return \Yii::$app->formatter->asDate($p_date,'php:d.m.Y');

  }

  public function to_beauty_date_time($p_date) {

    return \Yii::$app->formatter->asDate($p_date,'php:d.m.Y H:i:s');

  }

}
