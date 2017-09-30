<?php

namespace common\base;


use yii\base\Model;

class BaseForm extends Model
{
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

    public static function checkNull($column, $class)
    {
        if (empty($column)) {
            throw new BaseException(BaseException::REQUIRED_PARAM_NOT_PROVIDED, '');
        }
        if (is_array($column)) {
            foreach ($column as $value) {
                if ($value === null) {
                    $comment = self::getColumnComment($value, $class);
                    throw new BaseException(BaseException::REQUIRED_PARAM_NOT_PROVIDED, $comment . '不能为空');
                }
            }
        } else {
            return self::checkNull([$column], $class);
        }

    }
}