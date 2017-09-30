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

    protected static $exceptions = [
        self::CODE_SERVER_ERROR => 'Server error',
        self::REQUIRED_PARAM_NOT_PROVIDED => 'Miss required param!',
        self::MODEL_SAVE_ERROR => 'Model save error',
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