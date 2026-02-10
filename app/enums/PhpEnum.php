<?php

namespace App\enums;

class PhpEnum {

    public static function LabelOf($key, $class = false, $lang=null) {
        //casue overrid for static class is not working in php
        if (!$class) {
            $class = get_called_class();
        }
        $names = $class::Labels($lang);
        return isset($names[$key]) ? $names[$key] : $key;
    }

    public static function LabelOfStyle($key, $class = false) {
        // casue overrid for static class is not working in php
        if (!$class) {
            $class = get_called_class();
        }
        $names = $class::LabelsStyle();
        return isset($names[$key]) ? $names[$key] : $key;
    }

    public static function LabelOfForm($key, $class = false) {
        //casue overrid for static class is not working in php
        if (!$class) {
            $class = get_called_class();
        }
        $names = $class::LabelsForm();
        return isset($names[$key]) ? $names[$key] : $key;
    }

    public static function Has($val, $class = false) {
        if (!$class) {
            $class = get_called_class();
        }
        return array_key_exists($val, $class::Labels());
    }

    public static function ValueOfFunction($key, $function, $class = false) {
        //casue overrid for static class is not working in php
        if (!$class) {
            $class = get_called_class();
        }
        $names = $class::$function();
        return isset($names[$key]) ? $names[$key] : $key;
    }

}
