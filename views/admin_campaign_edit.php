<?php if(!in_array('edited_campaign', FlexibleABResultsController::getInstance()->modelBackendRequest->actions)) : ?>

  <form id="<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>-container"
        class="form form-horizontal"
        method="POST">
    <?php
      $campaignInformation = FlexibleABResultsController::getInstance()->entityCampaign->getById($_GET['campaign-id']);
    ?>
    <h2><?php echo __("Edit Campaign - " . $campaignInformation->name); ?></h2>
    <input type="hidden"
           name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>action-campaign-edit"
           value="yes"
        />
    <input type="hidden"
           name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_edit-id"
           value="<?php echo $campaignInformation->id;?>"
        >
    <?php
      require(FlexibleABResultsController::getInstance()->pluginFilePath . 'views/_admin_campaign_form.php');
    ?>
  </form>

<?php else: ?>

  <script type="text/javascript">
    jQuery(document).ready(function() {
      window.location = '?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug . '&sub-page=view&campaign-id=' . $_GET['campaign-id'];?>';
    });
  </script>

<?php endif;?>

<style>
  .box-sizing-normal *,
  .colorpicker-container *,
  .colorpicker-container *:after,
  .colorpicker-container *:before {
    -webkit-box-sizing: content-box !important;
    -moz-box-sizing: content-box !important;
    box-sizing: content-box !important;
  }
</style>