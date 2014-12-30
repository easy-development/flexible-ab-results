<?php

class FlexibleABResultsCampaign extends FlexibleABResultsAbstractDatabase {

  private $_table_name           = 'flexible_ab_results_campaign';

  const TYPE_REDIRECT  = 1;
  const TYPE_DISPLAY   = 2;
  const TYPE_IFRAME    = 3;
  public $typeList      = array(
    self::TYPE_REDIRECT => 'Redirect to Page',
    self::TYPE_DISPLAY  => 'Smart Page Display',
    self::TYPE_IFRAME   => 'iFrame Page Display'
  );

  public function init() {
    $this->_table_name  = $this->wp_db->base_prefix . $this->_table_name;
  }

  public function getTableName() {
    return $this->_table_name;
  }

  public function getAll() {
    $sql = 'SELECT campaign.*
                   FROM `' . $this->_table_name . '` campaign';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $this->_beforeGet($information);
  }

  public function getById($id) {
    $sql = 'SELECT campaign.*
                   FROM `' . $this->_table_name . '` campaign
                   WHERE `campaign`.`id` = "' . intval($id) . '"';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $this->_beforeGet($information);
  }

  public function getByPageId($pageID) {
    $sql = 'SELECT campaign.*
                   FROM `' . $this->_table_name . '` campaign
                   WHERE `campaign`.`page_id` = "' . intval($pageID) . '"';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $this->_beforeGet($information);
  }

  public function _beforeGet($information) {
    return $information;
  }

  public function _beforeInsert(&$information) {
    return $this;
  }

  public function _beforeSave(&$information) {
    return $this;
  }

  public function updateByID($information, $id) {
    $this->update($information, array("id" => $id));
  }

  public function deleteByID($id) {
    $this->delete(array('id' =>  $id));
  }

  public function cleanDeleteByID($id) {
    $this->deleteByID($id);
    FlexibleABResultsController::getInstance()->entityCampaignOption->deleteAllByCampaignID($id);
    FlexibleABResultsController::getInstance()->entityCampaignOptionDisplay->deleteAllByCampaignID($id);
  }

}