<?php

namespace common\util;


class UtilObject
{
    /**
     * 驼峰转下划线
     */
    private function humpToLine($str){
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
        return $str;
    }

}