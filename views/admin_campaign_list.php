<?php $campaignList = FlexibleABResultsController::getInstance()->entityCampaign->getAll(); ?>

<a class="btn btn-success" href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&sub-page=add-new">
  <?php echo __('Add New')?>
</a>

<div class="clearfix"></div>

<br/>

<form method="POST" id="<?php echo FlexibleABResultsController::getInstance()->pluginSlug; ?>-container" onkeypress="return event.keyCode != 13;">
  <?php if(!empty($campaignList)) : ?>

    <?php if(count($campaignList) > 5) : ?>
    <div class="row">
      <div class="col-md-10">
        <label for="filterEntries" class="col-sm-2 control-label"><?php echo __("Filter Entries"); ?></label>
        <div class="col-sm-6">
          <input id="filterEntries" type="text" name="search" style="width:100%;"/>
        </div>
      </div>
      <div class="col-sm-6">
        <p class="text-center" id="filterEntriesInformation"></p>
      </div>
    </div>
    <?php endif;?>

    <table class="table table-striped">
      <thead>
        <tr>
          <td><?php echo __('Name');?></td>
          <td><?php echo __('Options');?></td>
          <td><?php echo __('Display Count');?></td>
          <td><?php echo __('Unique Audience');?></td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach($campaignList as $campaignInformation) : ?>
          <tr class="filter-entries-entry" data-filter="<?php echo $campaignInformation->name;?>">
            <td><?php echo $campaignInformation->name;?></td>
            <td><?php echo count(FlexibleABResultsController::getInstance()->entityCampaignOption->getAllByCampaignID($campaignInformation->id)); ?></td>
            <td><?php echo count(FlexibleABResultsController::getInstance()->entityCampaignOptionDisplay->getAllByCampaignID($campaignInformation->id)); ?></td>
            <td><?php echo count(FlexibleABResultsController::getInstance()->entityCampaignOptionDisplay->getAllUniqueByCampaignID($campaignInformation->id)); ?></td>
            <td style="width: 220px;">
              <a class="btn btn-success hint--top"
                 data-hint="<?php echo __("View");?>"
                 href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&sub-page=view&campaign-id=<?php echo $campaignInformation->id;?>">
                <span class="glyphicon glyphicon-eye-open"></span>
              </a>
              <a class="btn btn-primary hint--top"
                 data-hint="<?php echo __("Edit");?>"
                 href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&sub-page=edit&campaign-id=<?php echo $campaignInformation->id;?>">
                <span class="glyphicon glyphicon-pencil"></span>
              </a>
              <a class="btn btn-warning hint--top"
                 data-hint="<?php echo __("Duplicate");?>"
                 href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&action=duplicate&campaign-id=<?php echo $campaignInformation->id;?>">
                <span class="glyphicon glyphicon-transfer"></span>
              </a>
              <a class="btn btn-danger hint--top"
                 data-hint="<?php echo __("Delete");?>"
                 href="?page=<?php echo FlexibleABResultsController::getInstance()->pluginSlug;?>&action=delete&campaign-id=<?php echo $campaignInformation->id;?>">
                <span class="glyphicon glyphicon-trash"></span>
              </a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  <?php else : ?>
      <div class="alert alert-info">
        <p><?php echo __('Start by adding your own first campaign timer. Click on the Add New button');?></p>
      </div>
  <?php endif;?>
</form>
