<html>
<head>
  <title><?php echo $campaignInformation->name;?></title>
  <style>
    body {
      overflow: hidden;
    }
    body, #iframe, #timer-container {
      margin  : 0;
      padding : 0;
      border  : 0;
    }
  </style>
</head>
<body>
<iframe id="iframe" src="<?php echo $deliveryTarget?>"></iframe>
<div id="timer-container"></div>

<script src="<?php echo FlexibleABResultsController::getInstance()->pluginURLPath;?>assets/jquery.js"></script>
<script type="text/javascript">
  var CampaignIframePage = {

    iframeObject         : {},

    Init : function() {
      this.iframeObject         = jQuery("#iframe");
      this.handleArrange();
    },

    handleArrange : function() {
      var objectInstance = this;
      this._arrangeIframeAndTimer();

      this.iframeObject.load(function(){
        objectInstance._arrangeIframeAndTimer();
      });

      jQuery(window).bind("resize", function(){
        objectInstance._arrangeIframeAndTimer();
      });
    },

    _arrangeIframeAndTimer : function() {
      this.iframeObject.css("width", jQuery(window).width());
      this.iframeObject.css("height", window.innerHeight);
    }

  };
  jQuery(document).ready(function(){
    CampaignIframePage.Init();
  });
</script>
</body>
</html>