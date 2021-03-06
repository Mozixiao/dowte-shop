<?php

namespace common\base;

use \Yii;
use yii\base\Exception;

class BaseException extends Exception
{
    protected $errorMessage = NULL;
    protected $errorCode = NULL;
    protected $params = [];

    const CODE_SERVER_ERROR = 500;
    const REQUIRED_PARAM_NOT_PROVIDED = 5001;
    const MODEL_SAVE_ERROR = 5002;

    const ACCESS_TOKEN_NOT_EXISTS = 6001;

    //file
    const FILE_UPLOAD_FAILED = 7001;
    const FILE_ALREADY_EXIST = 7002;
    const FILE_SAVE_FAILED = 7003;

    //ocr
    const RECOGNIZE_FALSE = 8001;

    protected static $exceptions = [
        self::CODE_SERVER_ERROR => 'Server error',
        self::REQUIRED_PARAM_NOT_PROVIDED => 'Miss required param',
        self::MODEL_SAVE_ERROR => 'Model save error',

        self::ACCESS_TOKEN_NOT_EXISTS => 'The access token is not exists',

        self::FILE_UPLOAD_FAILED => 'File uploads failed',
        self::FILE_ALREADY_EXIST => 'The file is already exist',
        self::FILE_SAVE_FAILED => 'Save files failed',

        self::RECOGNIZE_FALSE => 'Recognize false',
    ];

    public function __construct($errorCode = NULL, $errorMessage = NULL, $params = [])
    {
        if (property_exists(Yii::$app->response, 'format')) {
            Yii::$app->response->format = 'json';
        }
        $errorCode = (int)$errorCode;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage ? $errorMessage : $this->getErrorMessageByErrorCode($errorCode);

        if (is_array($params)) {
            $this->params = $params;

        } else {
            $this->params = [$params];
        }
        $this->_log();
        parent::__construct($this->errorMessage, $this->errorCode);
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getParams()
    {
        return $this->params;
    }

    public static function getErrorMessageByErrorCode($code = NULL)
    {
        return isset(self::$exceptions[(int)$code]) ? self::$exceptions[(int)$code] : NULL;
    }

    public function _log()
    {
        error_log("$this->file#$this->line|$this->errorCode|".self::getErrorMessageByErrorCode($this->errorCode)."|params:".json_encode($this->params));
    }
}