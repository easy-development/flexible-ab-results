<?php if(!in_array('new_campaign', FlexibleABResultsController::getInstance()->modelBackendRequest->actions)) : ?>
  <h2><?php echo __("Create New Campaign"); ?></h2>
  <form id="<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>-container"
        class="form form-horizontal"
        method="POST">
    <input type="hidden"
           name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>action-campaign-new"
           value="yes"
        />
    <?php require(FlexibleABResultsController::getInstance()->pluginFilePath . 'views/_admin_campaign_form.php'); ?>

  </form>
<?php else: ?>

  <script type="text/javascript">
    jQuery(document).ready(function() {
      window.location = '?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug . '&sub-page=view&campaign-id=' . FlexibleABResultsController::getInstance()->entityCampaign->getMySQLInsertID();?>';
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