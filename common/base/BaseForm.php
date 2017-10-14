<?php

namespace common\base;

use common\util\UtilObject;
use yii\base\Model;

class BaseForm extends Model
{
    /**
     * @param string $column 列名
     * @param $class
     * @return mixed
     * @throws BaseException
     */
    public static function getColumnComment($column, $class)
    {
        $prefix = 'common\models\\';
        $classArr = explode("\\", $class);
        $lastItem = array_pop($classArr);

        if (strpos($class, '\validators\\') > 0) {
            $modelName = substr($lastItem, 0, -9);

        } else {
            $modelName = substr($lastItem, 0, -4);
        }
        $modelCls = $prefix . $modelName;
        $model = new $modelCls;
        $attributeLabels = $model->attributeLabels();

        if ( ! isset($attributeLabels[$column])) {
            throw new BaseException(BaseException::CODE_SERVER_ERROR, '');
        }
        $comment = $attributeLabels[$column];

        return $comment;
    }

    /**
     * @param array $column
     * @param $class
     * @throws BaseException
     */
    public static function checkNull(array $column = [], $class)
    {
        if (empty($column)) {
            throw new BaseException(BaseException::REQUIRED_PARAM_NOT_PROVIDED, '');
        }

        foreach ($column as $k => $value) {
            if ($value === null) {
                $k = UtilObject::humpToLine($k);
                $comment = self::getColumnComment($k, $class);
                throw new BaseException(BaseException::REQUIRED_PARAM_NOT_PROVIDED, $comment . '不能为空');
            }
        }
    }
}