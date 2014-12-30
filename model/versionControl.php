<?php

class FlexibleABResultsVersionControl {

  public $wpOptionDatabaseVersion = 'database_version';
  public $currentDatabaseVersion  = '1.0';

  public $databaseUpdateVersion = array(
  );

  public function __construct($init = true) {
    $this->wpOptionDatabaseVersion = FlexibleABResultsController::getInstance()->pluginPrefix . $this->wpOptionDatabaseVersion;

    if($init)
      $this->init();
  }

  public function init() {
    if(get_option($this->wpOptionDatabaseVersion) != $this->currentDatabaseVersion)
      foreach($this->databaseUpdateVersion as $versionAlias => $versionFile)
        if(floatval($versionAlias) > floatval(get_option($this->wpOptionDatabaseVersion)))
          $this->_updateDatabaseToVersion($versionAlias, $versionFile);

  }

  private function _updateDatabaseToVersion($versionAlias, $versionFile) {
    global $wpdb;

    $query = file_get_contents(
        FlexibleABResultsController::getInstance()->pluginFilePath
        . 'model/sql-update/'
        . $versionFile
    );

    if($query == false)
      throw new Exception(FlexibleABResultsController::getInstance()->pluginName . ', missing DB UPDATE File');

    $query = str_replace('flexible_ab_results_' , $wpdb->base_prefix . 'flexible_ab_results_', $query);
    $queries = explode(';', $query);


    foreach($queries as $query)
      if(strlen($query)> 20)
        $response = $wpdb->query($query);

    $this->setCurrentDatabaseVersion($versionAlias);

    FlexibleABResultsController::getInstance()->modelBackendRequest->notifications['success'][] = 'AUTO : Database successfully updated to version ' . $versionAlias;
  }

  public function setCurrentDatabaseVersion($currentVersion) {
    $this->currentDatabaseVersion = $currentVersion;
    update_option($this->wpOptionDatabaseVersion, $this->currentDatabaseVersion);
  }

}