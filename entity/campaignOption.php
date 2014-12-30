<?php

class FlexibleABResultsCampaignOption extends FlexibleABResultsAbstractDatabase {

  private $_table_name = 'flexible_ab_results_campaign_option';

  public function init() {
    $this->_table_name  = $this->wp_db->base_prefix . $this->_table_name;
  }

  public function getTableName() {
    return $this->_table_name;
  }

  public function getAll() {
    $sql = 'SELECT click.*
                   FROM `' . $this->_table_name . '` click';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $information;
  }

  public function getById($id) {
    $sql = 'SELECT option.*
                   FROM `' . $this->_table_name . '` option
                   WHERE option.`id` = "' . intval($id) . '"';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $information;
  }

  public function getNaturalDeliveryPersistentLink($campaignID) {
    $sql = 'SELECT * FROM wp_flexible_ab_results_campaign_option op
                     WHERE `campaign_id` = ' . intval($campaignID) . '
                     ORDER BY IFNULL(
                         100 / ( density * (
                           SELECT count(cp.id)
                             FROM wp_flexible_ab_results_campaign_option_display cp
                             WHERE cp.campaign_id = op.campaign_id
                               AND cp.link = op.link
                             GROUP BY cp.ip_address
                           )
                         )
                     , 100) DESC
                     LIMIT 1';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $information->link;
  }

  public function getNaturalDeliveryLink($campaignID) {
    $sql = 'SELECT * FROM wp_flexible_ab_results_campaign_option op
                     WHERE `campaign_id` = ' . intval($campaignID) . '
                     ORDER BY IFNULL(
                         100 / ( density * (
                           SELECT count(cp.id)
                             FROM wp_flexible_ab_results_campaign_option_display cp
                             WHERE cp.campaign_id = op.campaign_id
                               AND cp.link = op.link
                           )
                         )
                     , 100) DESC';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $information->link;
  }

  public function getByCampaignIDAndLink($campaignID, $link) {
    $sql = 'SELECT *
                   FROM `' . $this->_table_name . '`
                   WHERE `campaign_id` = "' . $campaignID . '"
                     AND `link` = "' . $link . '"';

    $information = $this->wp_db->get_row($sql);

    return $information === null ? false : $information;
  }

  public function getAllByCampaignID($campaignID) {
    $sql = 'SELECT *
                   FROM `' . $this->_table_name . '`
                   WHERE `campaign_id` = "' . intval($campaignID) . '"';

    $information = $this->wp_db->get_results($sql);

    return $information === null ? array() : $information;
  }

  public function setOptionsFromList($campaignID, $optionsList) {
    $this->deleteAllByCampaignID($campaignID);
    $this->addOptionsFromList($campaignID, $optionsList);
  }

  public function addOptionsFromList($campaignID, $optionsList) {
    foreach($optionsList as $option) {
      $option = (is_object($option) ? get_object_vars($option) : $option);

      if(isset($option['id']))
        unset($option['id']);

      $option['campaign_id'] = $campaignID;

      $this->insert($option);
    }
  }

  public function deleteAllByCampaignID($campaignID) {
    $this->delete(array('campaign_id' => $campaignID));
  }

}