<?php

namespace App\enums;


class TaskStatus extends PhpEnum {

    const pending = 1;
    const in_progress = 2;
    const completed = 3;
    const canceled = 4;

    public static function Labels() {
        return [
            self::pending => trans('Pending'),
            self::in_progress => trans('In Progress'),
            self::completed => trans('Completed'),
            self::canceled => trans('Canceled'),
        ];
    }

    public static function LabelsStyle() {
        return [
            self::pending => '<span class=" badge badge-sm" style="color: white; background-color: #607d8b">'
                .'Pending'.'</span>',

            self::in_progress => '<span class="badge badge-sm" style="color: white; background-color: #607d8b">'
                .'In Progress'.'</span>',

            self::completed => '<span class="badge badge-sm" style="color: white; background-color: #607d8b">'
                .'Completed'.'</span>',

            self::canceled => '<span class="badge badge-sm" style="color: white; background-color: #607d8b">'
                .'Canceled'.'</span>',
        ];
    }

}
