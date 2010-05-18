<?php 
  $title = 'Zayso - Index';
  
  // Additional files being tested by this action
  $jsFilesx = array
  (
  
  );
  $memberId = 1;
?>
<script type="text/javascript">

Ext.ns('Zayso');

function doit()
{
  Ext.QuickTips.init();
  Ext.Direct.addProvider(Zayso.Direct.API());
  
  Zayso.app = new Zayso.App();
  
  var memberId = <?php echo $memberId; ?>;
  
  Zayso.app.execute({memberId: memberId});

}
Ext.onReady(doit);
</script>
 