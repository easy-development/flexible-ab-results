<?php
/*
Plugin Name: Flexible AB Results
Plugin URI: http://www.easy-development.com
Description: Flexible AB Results is an easy way to manage AB Testing on various websites.
Version: 1.0.0
Author: Andrei-Robert Rusu
Author URI: http://www.easy-development.com
*/

class FlexibleABResultsController {

  protected static $_instance;

  public static function getInstance() {
    if(self::$_instance === null)
      self::$_instance = new self();

    return self::$_instance;
  }

  public $pluginName       = 'Flexible AB Results';
  public $pluginSlug       = 'flexible-ab-results';
  public $pluginPrefix     = 'flexible_ab_results_';

  public $pluginFilePath;
  public $pluginURLPath;

  /**
   * @var FlexibleABResultsVersionControl
   */
  public $modelVersionControl;
  /**
   * @var FlexibleABResultsBackendRequest
   */
  public $modelBackendRequest;
  /**
   * @var FlexibleABResultsPageRequest
   */
  public $modelPageRequest;

  /**
   * @var FlexibleABResultsCampaign
   */
  public $entityCampaign;

  /**
   * @var FlexibleABResultsCampaignOption
   */
  public $entityCampaignOption;

  /**
   * @var FlexibleABResultsCampaignOptionDisplay
   */
  public $entityCampaignOptionDisplay;

  public function __construct() {
    $this->_setDependencies();
    $this->_includeModelsAndEntities();

    register_activation_hook(__FILE__, array($this, '_internalActivationHook'));
    add_action( "init", array($this, "_initHook"));
    add_action( 'admin_menu', array( $this, '_menuHook' ) );
    add_action( 'admin_enqueue_scripts', array($this, '_assetsHook') );
  }

  private function _setDependencies() {
    $this->pluginFilePath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    $this->pluginURLPath  = plugin_dir_url(__FILE__);
  }

  private function _includeModelsAndEntities() {
    require_once($this->pluginFilePath . 'model/versionControl.php');
    require_once($this->pluginFilePath . 'model/backendRequest.php');
    require_once($this->pluginFilePath . 'model/pageRequest.php');

    require_once($this->pluginFilePath . 'entity/_abstractDatabase.php');
    require_once($this->pluginFilePath . 'entity/campaign.php');
    require_once($this->pluginFilePath . 'entity/campaignOption.php');
    require_once($this->pluginFilePath . 'entity/campaignOptionDisplay.php');
  }

  public function _menuHook() {
    add_menu_page(
        $this->pluginName,
        $this->pluginName,
        'manage_options',
        $this->pluginSlug,
        array(
            $this, 'displayAdministration'
        ),
        $this->pluginURLPath . 'assets/icon.png'
    );
  }

  public function _initHook() {
    $this->_initModelsAndEntities();
  }

  private function _initModelsAndEntities() {
    $this->entityCampaign              = new FlexibleABResultsCampaign();
    $this->entityCampaignOption        = new FlexibleABResultsCampaignOption();
    $this->entityCampaignOptionDisplay = new FlexibleABResultsCampaignOptionDisplay();

    $this->modelVersionControl   = new FlexibleABResultsVersionControl();
    $this->modelBackendRequest   = new FlexibleABResultsBackendRequest();
    $this->modelPageRequest      = new FlexibleABResultsPageRequest();
  }

  public function displayAdministration() {
    $this->modelBackendRequest->process();

    echo '<div class="bootstrap_environment">';
    require_once('views/_header.php');

    if(isset($_GET['sub-page']) && $_GET['sub-page'] == 'help')
      require('views/help.php');
    else if(isset($_GET['sub-page']) && $_GET['sub-page'] == 'add-new')
      require('views/admin_campaign_add.php');
    else if(isset($_GET['sub-page']) && $_GET['sub-page'] == 'edit')
      require('views/admin_campaign_edit.php');
    else if(isset($_GET['sub-page']) && $_GET['sub-page'] == 'view')
      require('views/admin_campaign_view.php');
    else
      require('views/admin_campaign_list.php');

    echo '</div>';
  }

  public function _assetsHook() {
    if(!(isset($_GET['page']) && $_GET['page'] == $this->pluginSlug))
      return;

    wp_enqueue_style( 'hint.css', plugins_url('assets/hint.css', __FILE__ ));

    wp_enqueue_script('jquery');
    wp_enqueue_script(
        'bootstrap',
        plugins_url('assets/bootstrap.min.js', __FILE__ ),
        array( 'jquery'), false, true
    );
    wp_enqueue_style( 'bootstrap', plugins_url('assets/admin-style-bootstrap.css', __FILE__) );
    wp_enqueue_style( $this->pluginSlug . '-admin', plugins_url('assets/admin-style.css', __FILE__) );
  }

  public function _internalActivationHook() {
    global $wpdb;

    $query = file_get_contents(dirname(__FILE__) . '/install.sql');

    $query = str_replace('flexible_ab_results_' ,
        $wpdb->base_prefix . 'flexible_ab_results_',
        $query);

    $queries = explode(';', $query);


    foreach($queries as $query)
      if(strlen($query)> 20)
        $response = $wpdb->query($query);

    if(!class_exists("FlexibleABResultsVersionControl"))
      require_once($this->pluginFilePath . 'model/versionControl.php');

    $inactiveVersionControl   = new FlexibleABResultsVersionControl(false);

    $inactiveVersionControl->setCurrentDatabaseVersion(
        $inactiveVersionControl->currentDatabaseVersion
    );
  }

}

FlexibleABResultsController::getInstance();