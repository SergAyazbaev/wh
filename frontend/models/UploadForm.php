<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFile'], 'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg',
                'maxFiles' => 1,
                'mimeTypes' => 'image/jpeg, image/png',
            ],
            [['imageFiles'], 'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg',
                'maxFiles' => 2,
                'mimeTypes' => 'image/jpeg, image/png',
            ],

        ];
    }

}