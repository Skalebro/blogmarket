<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model {

    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'image'],
            [['image'], 'file', 'extensions' => 'jpg, png'],

        ];
    }

    public function uploadFile(UploadedFile $file, $currentImage)
    {
        $this->image = $file;
        if($this->validate()){
            $this->deleteUploadFile($currentImage);

            $filename = $this->createFileName($file);
            $file->saveAs('uploads/' . $filename);
            return $filename;
        }else{
            \Yii::$app->session->setFlash('success', "Image don`t uploaded");
        }
    }

    public function deleteUploadFile($currentImage)
    {
        if(!is_null($currentImage)){
            if (file_exists('uploads/' . $currentImage)) {
                unlink('uploads/' . $currentImage);
            }
        }
    }

    public function createFileName($file)
    {
        return strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
    }
}