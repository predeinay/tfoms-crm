<?php

namespace backend\models\upload;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadBase extends Model {

  public $file;
  public $file_name;
  public $file_path;

  public function rules() {
      return [
          [['file'], 'file', 'skipOnEmpty' => false],
      ];
  }

  public function upload() {
    
    $base_path = 'uploads';
    $upload_path = '/'.date("Y").'/'.date("m").'/'.date("d");

      if ($this->validate()) {
          $this->file_name = uniqid().$this->file->baseName . '.' . $this->file->extension;
          $this->file_path = $base_path.$upload_path;
          if (!file_exists($this->file_path)) {
            mkdir($this->file_path,0777,true);
          }
          $this->file->saveAs($this->file_path .'/'. $this->file_name);
          return true;
      } else {
          return false;
      }

  }

}
