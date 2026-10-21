<?php

namespace Modules\Blog\Helpers;

/**
 * Description of Alert
 *
 * @author Gholamreza
 */
class Alert
{
    const ALERT_SUCCESS = 'alert-success';
    const ALERT_DANGER = 'alert-danger';
    const ALERT_WARNING = 'alert-warning';
    const ALERT_INFO = 'alert-info';

    private $model;
    private $message;

    public function __construct($message, $model = self::ALERT_SUCCESS)
    {
        $this->setModel($model);
        $this->setMessage($message);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getFrontendModel()
    {
        switch ($this->model) {
            case self::ALERT_SUCCESS:
                return 'success';
            case self::ALERT_DANGER:
                return 'danger';
            case self::ALERT_WARNING:
                return 'warning';
            case self::ALERT_INFO:
                return 'info';

        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function setModel($model)
    {
        $this->model = $model;
    }

    private function setMessage($message)
    {
        $this->message = $message;
    }


    public function expose()
    {
        return array(
            'model' => $this->getModel(),
            'message' => $this->getMessage()
        );
    }

}
