<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm_mts_change extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;
//    public $imageFiles_old;
//    public $imageFiles_new;


    public function rules()
    {
        return [
            [['imageFiles'], 'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg',
                'maxFiles' => 4,
                'mimeTypes' => 'image/jpeg, image/png',
            ],
//            [['imageFiles_old'], 'file',
//                'skipOnEmpty' => false,
//                'extensions' => 'png, jpg',
//                'maxFiles' => 4,
//                'mimeTypes' => 'image/jpeg, image/png',
//            ],
//            [['imageFiles_new'], 'file',
//                'skipOnEmpty' => false,
//                'extensions' => 'png, jpg',
//                'maxFiles' => 4,
//                'mimeTypes' => 'image/jpeg, image/png',
//            ],


        ];
    }

}