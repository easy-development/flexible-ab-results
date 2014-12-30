<?php $args = array(
    'sort_order' => 'ASC',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'meta_key' => '',
    'meta_value' => '',
    'authors' => '',
    'child_of' => 0,
    'exclude_tree' => '',
    'number' => '',
    'offset' => 0,
    'post_type' => 'page'
);
$pageList = get_pages($args);
?>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-3 control-label hint--top"
             data-hint="Nickname or Alias for your A/B Testing Campaign">
        <?php echo __("Campaign Name");?> :
      </label>
      <div class="col-sm-9">
        <input type="text"
               class="form-control"
               name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_info[name]"
               value="<?php echo isset($campaignInformation->name) ? $campaignInformation->name : '';?>"
            >
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label hint--top"
             data-hint="The desired page / place where you want this campaign to happen.">
        <?php echo __("Desired Campaign Page");?> :
      </label>
      <div class="col-sm-9">
        <select class="form-control"
                name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_info[page_id]">
          <option value="0">None</option>
          <?php foreach($pageList as $pageItem) : ?>
          <option value="<?php echo $pageItem->ID; ?>"
                  <?php echo $pageItem->ID == (isset($campaignInformation->page_id) ? $campaignInformation->page_id : 0) ? 'selected="selected"' : '';?>
              ><?php echo $pageItem->post_title; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label hint--top"
             data-hint="Chose the method you want to deliver your visitors to your campaign.">
        <?php echo __("Delivery Method");?> :
      </label>
      <div class="col-sm-9">
        <select class="form-control"
                name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_info[type]">
          <?php foreach(FlexibleABResultsController::getInstance()->entityCampaign->typeList as $key => $value) : ?>
            <option value="<?php echo $key; ?>"
                <?php echo $key == (isset($campaignInformation->type) ? $campaignInformation->type : 0) ? 'selected="selected"' : '';?>
                ><?php echo $value; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label hint--top"
             data-hint="<?php echo __("An Persistent Campaign will make sure people will always be served the page that they've been previously served")?>">
        <?php echo __("Persistent Campaign");?> :
      </label>
      <div class="col-sm-9">
        <input type="checkbox"
               name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_info[is_persistent]"
               value="1"
               <?php echo isset($campaignInformation->is_persistent) && $campaignInformation->is_persistent == 1? 'checked="checked"' : '';?>
            >
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-9 col-md-offset-3">
        <table class="table table-striped">
          <thead>
          <tr>
            <th><span><?php echo __("Page Link")?></span></th>
            <th><span><?php echo __("Density")?></span></th>
            <th><span><?php echo __("Active")?></span></th>
            <th></th>
          </tr>
          </thead>
          <tbody id="new-line-container">
            <?php if(isset($campaignInformation->id)) : ?>
              <?php foreach(FlexibleABResultsController::getInstance()->entityCampaignOption->getAllByCampaignID($campaignInformation->id) as $campaignOption) :  ?>
                  <tr>
                    <td>
                      <input type="text"
                             name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[<?php echo $campaignOption->id;?>][link]"
                             value="<?php echo $campaignOption->link;?>"
                          >
                    </td>
                    <td>
                      <input type="text"
                             name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[<?php echo $campaignOption->id;?>][density]"
                             value="<?php echo $campaignOption->density;?>"
                          >
                    </td>
                    <td>
                      <input type="hidden"
                             name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[<?php echo $campaignOption->id;?>][is_active]"
                             value="0"
                          >
                      <input type="checkbox"
                             name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[<?php echo $campaignOption->id;?>][is_active]"
                             <?php echo $campaignOption->is_active == 1 ? 'checked="checked"' : '';?>
                             value="1"
                          >
                    </td>
                    <td style="width:100px;">
                      <a data-line-operation="remove" class="btn btn-danger"><?php echo __("Remove")?></a>
                    </td>
                  </tr>
              <?php endforeach; ?>
            <?php endif;?>
            <tr id="new-line-copy">
              <td>
                <input type="text" data-name-value="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[%rand%][link]">
              </td>
              <td>
                <input type="text" data-name-value="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[%rand%][density]">
              </td>
              <td>
                <input type="hidden" data-name-value="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[%rand%][is_active]" value="0">
                <input type="checkbox" data-name-value="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign_option[%rand%][is_active]" value="1">
              </td>
              <td style="width:100px;">
                <a id="trigger-new-line-copy" class="btn btn-success"><?php echo __("Add")?></a>
                <a data-line-operation="remove" class="btn btn-danger"><?php echo __("Remove")?></a>
              </td>
            </tr>
          </tbody>
        </table>

        <input type="submit" name="<?php echo FlexibleABResultsController::getInstance()->pluginPrefix;?>campaign-submit" class="btn btn-success" value="<?php echo __("Save Campaign");?>"/>
      </div>
    </div>
  </div>
</div>

<style>
  #new-line-copy [data-line-operation] {
    display: none;
  }
</style>

<script type="text/javascript">
  jQuery(document).ready(function(){
    var newLineContainerObject = jQuery("#new-line-container"),
        newLineObject          = jQuery("#new-line-copy"),
        newLineCopyTriggerID   = 'trigger-new-line-copy';

    jQuery("#" + newLineCopyTriggerID).bind("click", function(){
      newLineObject.clone().prependTo(newLineContainerObject);

      var currentLineObject = newLineContainerObject.find("> tr:first");
          currentLineObject.removeAttr("id");

      var randomString = Math.random().toString(36).substr(2, 5);

      currentLineObject.find("[data-name-value]").each(function(){ jQuery(this).attr('name', jQuery(this).attr('data-name-value').replace("%rand%", randomString)); });
      currentLineObject.find("#trigger-new-line-copy").remove();
      newLineObject.find("[data-name-value]").each(function(){ jQuery(this).val(''); });
    });

    newLineContainerObject.on('click', '[data-line-operation="remove"]', function(){
      jQuery(this).parents('tr:first').remove();
    });
  });
</script>