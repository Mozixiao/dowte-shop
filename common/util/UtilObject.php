<?php

namespace common\util;


class UtilObject
{
    /**
     * 驼峰转下划线
     */
    public static function humpToLine($str){
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
        return $str;
    }

    /**
     * @param string $var   要查找的变量
     * @param array|null  $scope 要搜寻的范围
     * @return string 变量名称
     */
    public static function getVariableName(&$var, $scope = null){

        $scope = $scope == null ? $GLOBALS : $scope; // 如果没有范围则在globals中找寻

        // 因有可能有相同值的变量,因此先将当前变量的值保存到一个临时变量中,然后再对原变量赋唯一值,以便查找出变量的名称,找到名字后,将临时变量的值重新赋值到原变量
        $tmp = $var;

        $var = 'tmp_value_' . mt_rand();
        $name = array_search($var, $scope, true); // 根据值查找变量名称

        $var = $tmp;
        return $name;
    }
}