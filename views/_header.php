<div class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>">
      <img src="<?php echo FlexibleABResultsController::getInstance()->pluginURLPath . 'assets/logo.png';?>"
           />
    </a>
  </div>
  <div class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li style="margin-bottom: 0" class="<?php echo !(isset($_GET['sub-page'])) ? 'active' : ''?>">
        <a href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>">
          <?php echo __('Administration')?>
        </a>
      </li>
      <li style="margin-bottom: 0" class="<?php echo (isset($_GET['sub-page']) && $_GET['sub-page'] == 'add-new') ? 'active' : ''?>">
        <a href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&sub-page=add-new">
          <?php echo __('Add New Campaign')?>
        </a>
      </li>
      <li style="margin-bottom: 0" class="<?php echo (isset($_GET['sub-page']) && $_GET['sub-page'] == 'help') ? 'active' : ''?>">
        <a href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&sub-page=help">
          <?php echo __('Help')?>
        </a>
      </li>
    </ul>
  </div>
</div>

<?php FlexibleABResultsController::getInstance()->modelBackendRequest->displayRequestNotifications(); ?>