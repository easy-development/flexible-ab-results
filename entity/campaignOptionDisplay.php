<?php

class FlexibleABResultsCampaignOptionDisplay extends FlexibleABResultsAbstractDatabase {

  private $_table_name = 'flexible_ab_results_campaign_option_display';

  public function init() {
    $this->_table_name  = $this->wp_db->base_prefix . $this->_table_name;
  }

  public function getTableName() {
    return $this->_table_name;
  }

  public function getAll() {
    $sql = 'SELECT display.*
                   FROM `' . $this->_table_name . '` display';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $information;
  }

  public function getAllByCampaignID($campaignID) {
    $sql = 'SELECT *
                   FROM `' . $this->_table_name . '`
                   WHERE `campaign_id` = "' . intval($campaignID) . '"';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $information;
  }

  public function getAllUniqueByCampaignID($campaignID) {
    $sql = 'SELECT *
                   FROM `' . $this->_table_name . '`
                   WHERE `campaign_id` = "' . intval($campaignID) . '"
                   GROUP BY ip_address';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $information;
  }

  public function getById($id) {
    $sql = 'SELECT display.*
                   FROM `' . $this->_table_name . '` display
                   WHERE `display`.`id` = "' . intval($id) . '"';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $information;
  }

  public function getByStatsByCampaignID($campaignID) {
    $sql = 'SELECT count(mainTable.id) as display_count,
                   IFNULL((SELECT count(distinct innerTable.ip_address) FROM `' . $this->_table_name . '` innerTable
                           WHERE innerTable.link        = mainTable.link
                             AND innerTable.campaign_id = mainTable.campaign_id
                           ), 0) as unique_count,
                   link
                   FROM `' . $this->_table_name . '` mainTable
                   WHERE mainTable.`campaign_id` = "' . $campaignID . '"
                   GROUP BY mainTable.link';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $information;
  }

  public function getLastDisplayByCampaignIDAndIPAddress($campaignID, $ipAddress) {
    $sql = 'SELECT display.*
                   FROM `' . $this->_table_name . '` display
                   WHERE `display`.`campaign_id` = "' . intval($campaignID) . '"
                     AND `display`.`ip_address`  = "' . htmlentities($ipAddress) . '"
                   ORDER BY `display`.`creation_date` DESC';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $information;
  }

  public function getDisplayCountMAPForInterval($from_time, $to_time, $campaign_id = false) {
    $sql = 'SELECT id, campaign_id, DATE(creation_date) as creation_date FROM ' . $this->_table_name . ' campaign_display
                     WHERE DATE(campaign_display.creation_date) >= "' . date ("Y-m-d", $from_time). '"
                       AND DATE(campaign_display.creation_date) <= "' . date ("Y-m-d", $to_time). '" ';

    if($campaign_id != false)
      $sql .= ' AND campaign_display.campaign_id = ' . intval($campaign_id);

    $information = $this->wp_db->get_results($sql);

    $return = array();

    for($i = $from_time; $i <= $to_time; $i += 86400)
      $return[date("Y-m-d", $i)] = 0;

    foreach($information as $info)
      $return[$info->creation_date]++;

    return $return;
  }

  public function getUniqueDisplayCountMAPForInterval($from_time, $to_time, $campaign_id = false) {
    $sql = 'SELECT id, campaign_id, DATE(creation_date) as creation_date FROM ' . $this->_table_name . ' campaign_display
                     WHERE DATE(campaign_display.creation_date) >= "' . date ("Y-m-d", $from_time). '"
                       AND DATE(campaign_display.creation_date) <= "' . date ("Y-m-d", $to_time). '"
                       GROUP BY DATE(campaign_display.creation_date), campaign_display.ip_address';

    if($campaign_id != false)
      $sql .= ' AND campaign_display.campaign_id = ' . intval($campaign_id);

    $information = $this->wp_db->get_results($sql);

    $return = array();

    for($i = $from_time; $i <= $to_time; $i += 86400)
      $return[date("Y-m-d", $i)] = 0;

    foreach($information as $info)
      $return[$info->creation_date]++;

    return $return;
  }

  public function deleteAllByCampaignID($campaignID) {
    $this->delete(array('campaign_id' => $campaignID));
  }

}