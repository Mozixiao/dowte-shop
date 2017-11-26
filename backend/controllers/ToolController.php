<?php

namespace backend\controllers;


use common\base\BaseEndController;
use common\base\BaseException;
use common\sdk\BaiduOcr;

class ToolController extends BaseEndController
{
    public function actionOcr()
    {
        if (\Yii::$app->request->isGet) {
            return $this->render('ocr');
        }
        if (\Yii::$app->request->isPost) {
            $ocrType = $_POST['ocr_type'];
            $baiduOcr = new BaiduOcr();
            if ($_FILES['file']['error'] > 0) {
                throw new BaseException(BaseException::FILE_UPLOAD_FAILED, "文件上传失败" . $_FILES['file']['error']);
            }

            $fileSrc = __DIR__ . '/../../uploads/' . time() . "_" . $_FILES['file']['name'];
            if (file_exists($fileSrc)) {
                throw new BaseException(BaseException::FILE_ALREADY_EXIST, "文件已存在" . $_FILES['file']['name']);
            }

            if ( ! move_uploaded_file($_FILES['file']['tmp_name'], $fileSrc)) {
                throw new BaseException(BaseException::FILE_SAVE_FAILED, "文件保存服务器失败" . $_FILES['file']['name']);
            }

            $fp = fopen($fileSrc, 'r');
            $file_content = base64_encode(fread($fp, filesize($fileSrc)));//base64编码
            fclose($fp);
            @unlink($fileSrc);
            return $this->renderMson($baiduOcr->ocr($file_content, $ocrType));
        }
        return $this->renderMson(false);
    }
}