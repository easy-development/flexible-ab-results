<?php

class FlexibleABResultsBackendRequest {

  public $notifications              = array(
    'errors'        => array(),
    'notifications' => array(),
    'success'       => array(),
    'warnings'      => array()
  );
  public $actions                    = array();
  public $_processRequestComplete    = 0;
  
  public function process() {
    if(!is_admin())
      return;

    $this->_init();
  }

  public function _init() {
    if(isset($_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'action-campaign-new']))
      $this->_addCampaignFormAction();

    if(isset($_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'action-campaign-edit']))
      $this->_editCampaignFormAction();

    if(isset($_GET['action']) && $_GET['action'] == 'delete') {
      $this->_deleteCampaignAction($_GET['campaign-id']);
    } else if(isset($_GET['action']) && $_GET['action'] == 'duplicate') {
      $this->_duplicateCampaignAction($_GET['campaign-id']);
    }
  }

  private function _addCampaignFormAction() {
    $insertInformation = $_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'campaign_info'];

    FlexibleABResultsController::getInstance()->entityCampaign->insert($insertInformation);
    $campaignID = FlexibleABResultsController::getInstance()->entityCampaign->getMySQLInsertID();
    FlexibleABResultsController::getInstance()->entityCampaignOption->addOptionsFromList(
      $campaignID, $_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'campaign_option']
    );

    $this->actions[] = 'new_campaign';
    $this->notifications['success'][] = 'Added Campaign "' . $insertInformation['name']. '"';
  }

  private function _editCampaignFormAction() {
    $updateInformation = $_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'campaign_info'];
    $updateID = $_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'campaign_edit-id'];

    FlexibleABResultsController::getInstance()->entityCampaign->updateByID($updateInformation, $updateID);
    FlexibleABResultsController::getInstance()->entityCampaignOption->setOptionsFromList(
        $updateID, $_POST[FlexibleABResultsController::getInstance()->pluginPrefix . 'campaign_option']
    );

    $this->actions[] = 'edited_campaign';
    $this->notifications['success'][] = 'Edited Campaign "' . $updateInformation['name']. '"';
  }

  private function _duplicateCampaignAction($campaignID) {
    $campaignInformation = FlexibleABResultsController::getInstance()->entityCampaign->getById($campaignID);

    if($campaignInformation !== false) {
      unset($campaignInformation->id);

      $campaignInformation->name .= __(" - Copy");

      FlexibleABResultsController::getInstance()->entityCampaign->insert(get_object_vars($campaignInformation));
      $copyCampaignID = FlexibleABResultsController::getInstance()->entityCampaign->getMySQLInsertID();
      FlexibleABResultsController::getInstance()->entityCampaignOption->addOptionsFromList(
          $copyCampaignID, FlexibleABResultsController::getInstance()->entityCampaignOption->getAllByCampaignID($campaignID)
      );


      $this->actions[] = 'duplicated_campaign';
      $this->notifications['notifications'][] = 'Duplicated Campaign "' . $campaignInformation->name . '"';
    }
  }

  private function _deleteCampaignAction($campaignID) {
    $campaignInformation = FlexibleABResultsController::getInstance()->entityCampaign->getById($campaignID);

    if($campaignInformation !== false) {
      FlexibleABResultsController::getInstance()->entityCampaign->cleanDeleteByID($campaignID);

      $this->actions[] = 'deleted_campaign';
      $this->notifications['errors'][] = 'Deleted Campaign "' . $campaignInformation->name . '"';
    }
  }

  public function displayRequestNotifications() {
    if(isset($this->notifications['errors']))
      foreach($this->notifications['errors'] as $error)
        echo '<div class="alert alert-danger">' . $error . '</div>';

    if(isset($this->notifications['notifications']))
      foreach($this->notifications['notifications'] as $notification)
        echo '<div class="alert alert-info">' . $notification . '</div>';

    if(isset($this->notifications['success']))
      foreach($this->notifications['success'] as $success)
        echo '<div class="alert alert-success">' . $success . '</div>';

    if(isset($this->notifications['warnings']))
      foreach($this->notifications['warnings'] as $warning)
        echo '<div class="alert alert-warning">' . $warning . '</div>';
  }

}