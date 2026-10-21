<?php

namespace Modules\Blog\Helpers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AlertHelper
 *
 * @author Gholamreza
 */
class AlertHelper
{
    private static $alerts = null;

    public static function show($message, $model = Alert::ALERT_SUCCESS)
    {
        $alert = new Alert($message, $model);
        self::addNewAlert($alert);
    }


    public static function showAjax($message, $model = Alert::ALERT_SUCCESS)
    {
        $alert = new Alert($message, $model);
        return $alert->expose();
    }

    private static function refresh()
    {
        self::$alerts = \Session::get("ALERT_HELPER_ALERTS");
    }

    private static function push()
    {
        \Session::put("ALERT_HELPER_ALERTS", self::$alerts);
    }

    private static function addNewAlert(Alert $alert)
    {
        self::refresh();
        self::$alerts[] = $alert;
        self::push();
    }

    public static function get($flush = false)
    {
        if (self::has()) {
            $alerts = \Session::get("ALERT_HELPER_ALERTS");
            if ($flush)
                self::flush();
            return $alerts;
        } else {
            return array();
        }
    }

    public static function flush()
    {
        \Session::pull("ALERT_HELPER_ALERTS");
        self::$alerts = null;
    }

    public static function has()
    {
        if (\Session::has("ALERT_HELPER_ALERTS"))
            return true;
        return false;
    }

}
