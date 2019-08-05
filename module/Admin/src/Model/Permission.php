<?php
namespace Admin\Model;

/**
 * Class Permission
 * @package Admin\Model
 */
class Permission {

    const PHOTOGRAPH_INFO_PERMISSION = 48;

    public $id;
    public $module;
    public $controller;
    public $action;
    public $description;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->module = (!empty($data['module'])) ? $data['module'] : null;
        $this->controller = (!empty($data['controller'])) ? $data['controller'] : null;
        $this->action = (!empty($data['action'])) ? $data['action'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module) {
        $this->module = $module;
    }

    /**
     * @return mixed
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
}