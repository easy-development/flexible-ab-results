<?php

class FlexibleABResultsPageRequest {

  public function __construct() {
    $postID = url_to_postid( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] );

    if($postID != 0) {
      $campaignInformation = $this->_campaignEntity()->getByPageId($postID);

      if($campaignInformation != false)
        $this->handleCampaignAB($campaignInformation);

    }
  }

  public function handleCampaignAB($campaignInformation) {
    $deliveryTarget = $this->_getCampaignDisplayLink($campaignInformation);

    $this->_recordCampaignDisplay($campaignInformation, $deliveryTarget);

    $this->_handleCampaignDeliveryTarget($campaignInformation, $deliveryTarget);
  }

  private function _getCampaignDisplayLink($campaignInformation) {
    if($campaignInformation->is_persistent) {
      $lastDisplayed = $this->_campaignOptionDisplayEntity()->getLastDisplayByCampaignIDAndIPAddress(
        $campaignInformation->id, $_SERVER['REMOTE_ADDR']
      );

      if(isset($lastDisplayed->link) &&
          $this->_isValidCampaignLink($campaignInformation, $lastDisplayed->link))
        return $lastDisplayed->link;
    }

    return $campaignInformation->is_persistent ?
        $this->_campaignOptionEntity()->getNaturalDeliveryPersistentLink(
        $campaignInformation->id
    ) : $this->_campaignOptionEntity()->getNaturalDeliveryLink(
        $campaignInformation->id
    );
  }

  private function _isValidCampaignLink($campaignInformation, $link) {
    return $this->_campaignOptionEntity()->getByCampaignIDAndLink(
        $campaignInformation->id, $link
    ) != false;
  }

  private function _recordCampaignDisplay($campaignInformation, $linkServed) {
    $this->_campaignOptionDisplayEntity()->insert(
        array(
            'campaign_id'  =>  $campaignInformation->id,
            'link'         =>  $linkServed,
            'ip_address'   =>  htmlentities($_SERVER['REMOTE_ADDR']),
            'server_date'  => date('Y-m-d H:i:s', time())
        )
    );
  }

  private function _handleCampaignDeliveryTarget($campaignInformation, $deliveryTarget) {
    if($campaignInformation->type == FlexibleABResultsCampaign::TYPE_DISPLAY) {
      $pageContent = @file_get_contents($deliveryTarget);

      if($pageContent == false)
        wp_redirect($deliveryTarget);
      else
        echo $pageContent;
    } else if($campaignInformation->type == FlexibleABResultsCampaign::TYPE_IFRAME) {
      require_once(FlexibleABResultsController::getInstance()->pluginFilePath . 'views/campaign_iframe_view.php');
    } else if($campaignInformation->type == FlexibleABResultsCampaign::TYPE_REDIRECT) {
      wp_redirect($deliveryTarget);
    } else {
      wp_redirect($deliveryTarget);
    }

    exit;
  }

  /**
   * @return FlexibleABResultsCampaign
   */
  private function _campaignEntity() {
    return FlexibleABResultsController::getInstance()->entityCampaign;
  }

  /**
   * @return FlexibleABResultsCampaignOption
   */
  private function _campaignOptionEntity() {
    return FlexibleABResultsController::getInstance()->entityCampaignOption;
  }

  /**
   * @return FlexibleABResultsCampaignOptionDisplay
   */
  private function _campaignOptionDisplayEntity() {
    return FlexibleABResultsController::getInstance()->entityCampaignOptionDisplay;
  }

}