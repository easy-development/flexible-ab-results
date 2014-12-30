<?php
  $campaignInformation = FlexibleABResultsController::getInstance()->entityCampaign->getById($_GET['campaign-id']);
?>

<h2>
  <?php echo $campaignInformation->name;?>
  Campaign -
  <?php echo $campaignInformation->is_persistent ? 'Persistent' : 'Flexible' ?> -
  <?php echo FlexibleABResultsController::getInstance()->entityCampaign->typeList[$campaignInformation->type];?>
</h2>

<?php $optionDisplayList = FlexibleABResultsController::getInstance()
                            ->entityCampaignOptionDisplay
                            ->getByStatsByCampaignID($campaignInformation->id);?>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Link</th>
      <th>Total Display</th>
      <th>Unique Display</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($optionDisplayList as $optionDisplay) : ?>
      <tr>
        <td><?php echo $optionDisplay->link;?></td>
        <td><?php echo $optionDisplay->display_count;?></td>
        <td><?php echo $optionDisplay->unique_count;?></td>
      </tr>
    <?php endforeach;?>
  </tbody>
</table>
<div class="alert alert-info">
  <?php echo __("Only Links that have been delivered atleast once will be displayed")?>
</div>