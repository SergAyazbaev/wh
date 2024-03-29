<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

//use yii\helpers\FileHelper;
//use yii\imagine\Image;
//use yii\helpers\Json;

//use Imagine\Image\Box;
//use Imagine\Image\Point;


class ImageUpload extends Model
{
    public $imageFile;


    public $file_upload;

    public $image;
    public $crop_info;


    public function rules()
    {
        return [
            [['imageFile'], 'required', 'message' => 'Надо заполнить...'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],


            [['file_upload'],
                'file',
                'types' => ['jpg', 'png', 'gif', 'jpeg'],
                'mimeTypes' => ['image/gif', 'image/jpeg', 'image/png', 'image/pjpeg'],
            ],

//            [['crop_info'],
//                'filter',
//                'filter' => function ($value) {
//                    return json_decode($value);
//                }
//            ],

//            [['image'],
//                'filter',
//                'filter' => function ($value) {
//                    return json_decode($value);
//                }
//            ],


        ];
    }

    /**
     * Физическая запись на диск
     * =
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function uploadFile(UploadedFile $file)
    {
        $this->imageFile = $file;
        $filenmame = mb_strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
        $file->saveAs(Yii::getAlias('@web') . 'uploads/' . $filenmame);

        //ddd($file);
        //ddd($this);

        return $file->name;
    }


//    public function afterSave($insert, $changedAttributes)
//    {
//        //  ...
//
//        ddd($this);
//
//        // open image
//        $image = Image::getImagine()->open($this->image->tempName);
//
//        // rendering information about crop of ONE option
//        $cropInfo = Json::decode($this->crop_info)[0];
//        $cropInfo['dWidth'] = (int)$cropInfo['dWidth']; //new width image
//        $cropInfo['dHeight'] = (int)$cropInfo['dHeight']; //new height image
//        $cropInfo['x'] = $cropInfo['x']; //begin position of frame crop by X
//        $cropInfo['y'] = $cropInfo['y']; //begin position of frame crop by Y
//        // Properties bolow we don't use in this example
//        //$cropInfo['ratio'] = $cropInfo['ratio'] == 0 ? 1.0 : (float)$cropInfo['ratio']; //ratio image.
//        //$cropInfo['width'] = (int)$cropInfo['width']; //width of cropped image
//        //$cropInfo['height'] = (int)$cropInfo['height']; //height of cropped image
//        //$cropInfo['sWidth'] = (int)$cropInfo['sWidth']; //width of source image
//        //$cropInfo['sHeight'] = (int)$cropInfo['sHeight']; //height of source image
//
//        //delete old images
//        $oldImages = FileHelper::findFiles(Yii::getAlias('@path/to/save/image'), [
//            'only' => [
//                $this->id . '.*',
//                'thumb_' . $this->id . '.*',
//            ],
//        ]);
//        for ($i = 0; $i != count($oldImages); $i++) {
//            @unlink($oldImages[$i]);
//        }
//
//        //saving thumbnail
//        $newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
//        $cropSizeThumb = new Box(200, 200); //frame size of crop
//        $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
//        $pathThumbImage = Yii::getAlias('@path/to/save/image')
//            . '/thumb_'
//            . $this->id
//            . '.'
//            . $this->image->getExtension();
//
//        $image->resize($newSizeThumb)
//            ->crop($cropPointThumb, $cropSizeThumb)
//            ->save($pathThumbImage, ['quality' => 100]);
//
//        //saving original
//        $this->image->saveAs(
//            Yii::getAlias('@path/to/save/image')
//            . '/'
//            . $this->id
//            . '.'
//            . $this->image->getExtension()
//        );
//    }


}