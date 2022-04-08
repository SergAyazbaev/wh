<?php

namespace frontend\controllers;

use frontend\models\ImageUpload;
use frontend\models\Pe_identification;
use yii\web\HttpException;

use frontend\models\Sklad;
use frontend\models\Sprwhelement;
use frontend\models\Tesseract;
use Yii;
use yii\web\Controller;


class UpController extends Controller
{


    public function beforeSave()
    {

        if ($file = CUploadedFile::getInstance($this, 'file_upload')) {
            $pathSave = Yii::getPathOfAlias('path.to.save');
            $fileName = md5(microtime() + rand(0, 1000)) . '.' . $file->getExtensionName();

            // original image
            $image = Yii::app()->image->load($file->getTempName());
            $image->save($pathSave . '/' . $fileName);

            // thumb image
            // size of thumb image, by default 200x200px
            $width = $height = 200;
            $image
                ->resize((int)$this->crop_info->dw, (int)$this->crop_info->dh)
                ->crop($width, $height, abs($this->crop_info->y), abs($this->crop_info->x))
                ->save($pathSave . '/_' . $fileName);
        }

        return true;
    }


    /**
     * INDEX
     *
     * {@inheritdoc}
     */
    public function actionIndex()
    {

        //PE
        $model = new Pe_identification();


        ddd($model);


        $sklad = Sklad::getSkladIdActive();


        $array_sklad_list = [];

        if (!isset($sklad) || empty($sklad)) {
            $array_sklad_list = Yii::$app->getUser()->identity->sklad;  // * All SKLAD's

            if (!is_array($array_sklad_list)) {
                if (isset($array_sklad_list) && !empty($array_sklad_list) && Sklad::setSkladIdActive($array_sklad_list)) {
                    $sklad = Sklad::getSkladIdActive();
                }
            } else {
                $array = [];
                foreach ($array_sklad_list as $item) {
                    $array [$item] = $item;
                }
                asort($array);
                $array_sklad_list = $array;
            }
        }

        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        $ap = Sklad::getApIdActive();
        $pe = Sklad::getPeIdActive();

        $array_full = Sprwhelement::findFullArray($pe);

        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];

        //ddd($array_full);


        return $this->render('/mobile/index', [
            "user_name" => $user_name,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

            'sklad' => $sklad,
            'array_sklad_list' => $array_sklad_list,
        ]);

    }


    /**
     * OCR - reader txt     *
     * -
     * Распознавание текста по ФОТО
     *=
     *
     */
    public function actionOcr()
    {
        $para = Yii::$app->request->queryParams;

        if (!isset($para) || !isset($path_for_photo) || empty($path_for_photo)) {
//            $path_for_photo = '8055.png';
//            $path_for_photo = '1.png'; // big text
//            $path_for_photo = '17.png'; ///Null
//            $path_for_photo = '16.jpg';
//            $path_for_photo = '18.jpg';

            $path_for_photo = '14.jpg';
//            $path_for_photo = '12.jpg';
        }


        $imgDir = DIRECTORY_SEPARATOR . 'photo';

        $imgFull = yii::getAlias('@path_img'); ///

        echo '<img src="' . $imgDir . DIRECTORY_SEPARATOR . $path_for_photo . '" />';
//        echo '<br>';
//        echo '<br>';
//        echo '<br>';


        /// РАБОТАЕТ!!!! DEBIAN
        //        $ocr = new TesseractOCR();
        $ocr = new Tesseract();

        $ocr->image($imgFull . DIRECTORY_SEPARATOR . $path_for_photo);
        $ocr->setWhitelist(range('a', 'z'), range('A', 'Z'), range(0, 9));
        $ocr->setLanguage('eng');
        //->recognize();
        //        $ocr->setPsm(2); //!
        $str = preg_split('/[ \\n]/', ($ocr)->run());        //$str = ($ocr)->run();

        //        ddd($ocr);

        ddd($str);


        //$ocr->setLanguage('eng')->recognize(true)->autofocus(true);

        //        $ver = ($ocr)->version();
        //        ddd($ver); // '4.0.0'


        //chmod(Yii::getAlias($tmpDir), 0777);

        /// РАБОТАЕТ!!!! Into WINDOWS
//        $text = (new TesseractOCR($imgFull . DIRECTORY_SEPARATOR . $path_for_photo))
//            //->executable('C:\Program Files (x86)\Tesseract-OCR\tesseract.exe')
//            ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
//            ->whitelist( range('A', 'Z'), range(0, 9))
//            ->run();
//        ddd($text);

        return '';

    }

    /**
     * OCR - reader txt     *
     * -
     * Распознавание текста по ФОТО
     *=
     *
     */
    public function actionGim()
    {
        //ddd(111111);

        return $this->render('gim/index');

    }

    /**
     * OCR - reader txt     *
     * -
     * Распознавание текста по ФОТО
     *=
     *
     */
    public function actionCrop_jq()
    {
        //ddd(111111);

        return $this->render('crop_jq/index');

    }


    /**
     *
     */
    public function actionCrop()
    {
        //PE
        $model = new Pe_identification();

        ///
        if ($model->load(Yii::$app->request->post())) {
            //ddd($_FILES);
            //ddd($model);

            $arr = json_decode($model->crop_info);
            $arr_0 = $arr[0];

            $img_body = $arr_0->image;
            $img_body = substr($img_body, 22, -1);
            $model->image = base64_decode($img_body);
            unset($model->crop_info);

            $model->imageName = $_FILES['Pe_identification'];
            $model->imageFile = ImageUpload::instance($model, 'image');


            /// UPLOAD OLD
            if (!$path_hash = $model->upload_crop()) {
                throw new HttpException(411, 'Не загрузил фото OLD');
            }

            ////////////////////
            unset($model->image);
            //ddd($model);

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }


        ////
        return $this->render('jq/index',
            [
                'model' => $model
            ]);

    }


}