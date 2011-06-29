<?php
  $data = $this->data;
  $ages = array('U10','U12','U14','U16','U19');
?>
<form method="post" action="projinfo-reflevel">
  <table border="1" class="form_table" style="width: 700px;">
    <tr><th colspan="22">Age Groups to Referee</th></tr>
    <tr>
      <td>Category</td>
      <td>NA</td>
      <?php foreach($ages as $age) { ?>
        <td colspan="4" align="center"><?php echo $age; ?></td>
      <?php } ?>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <?php foreach($ages as $age) { ?>
        <td colspan="2" align="center">Boys</td>
        <td colspan="2" align="center">Girls</td>
      <?php } ?>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <?php foreach($ages as $age) { ?>
        <td align="center">CR</td>
        <td align="center">AR</td>
        <td align="center">CR</td>
        <td align="center">AR</td>
      <?php } ?>
    </tr>
    <?php
    foreach($data->levels as $level)
    {
      $desc = $level['desc'];
      $cat  = $level['cat'];
    ?>
    <tr>
      <td><?php echo $desc; ?></td>
      <?php echo $this->genCheckBox($cat,'NA'); ?>
      <?php echo $this->genCheckBox($cat,'U10BCR'); ?>
      <?php echo $this->genCheckBox($cat,'U10BAR'); ?>
      <?php echo $this->genCheckBox($cat,'U10GCR'); ?>
      <?php echo $this->genCheckBox($cat,'U10GAR'); ?>

      <?php echo $this->genCheckBox($cat,'U12BCR'); ?>
      <?php echo $this->genCheckBox($cat,'U12BAR'); ?>
      <?php echo $this->genCheckBox($cat,'U12GCR'); ?>
      <?php echo $this->genCheckBox($cat,'U12GAR'); ?>

      <?php echo $this->genCheckBox($cat,'U14BCR'); ?>
      <?php echo $this->genCheckBox($cat,'U14BAR'); ?>
      <?php echo $this->genCheckBox($cat,'U14GCR'); ?>
      <?php echo $this->genCheckBox($cat,'U14GAR'); ?>

      <?php echo $this->genCheckBox($cat,'U16BCR'); ?>
      <?php echo $this->genCheckBox($cat,'U16BAR'); ?>
      <?php echo $this->genCheckBox($cat,'U16GCR'); ?>
      <?php echo $this->genCheckBox($cat,'U16GAR'); ?>

      <?php echo $this->genCheckBox($cat,'U19BCR'); ?>
      <?php echo $this->genCheckBox($cat,'U19BAR'); ?>
      <?php echo $this->genCheckBox($cat,'U19GCR'); ?>
      <?php echo $this->genCheckBox($cat,'U19GAR'); ?>
    </tr>
    <?php } ?>
  <tr>
    <td colspan="18">Make changes then press Update at least once. Click <a href="home">Home</a> when done.</td>
    <td align="center" colspan="2"><input type="reset"  name="ref_level_reset"  value="Reset" />
    <td align="center" colspan="2"><input type="submit" name="ref_level_submit" value="Update" />
  </tr>
  </table>
</form>
<h3>Help</h3>
<p>Press Update at least once even if don't plan on refereeing at all.  This lets the system know that you have seen the form. </p>
<h3>Categories</h3>
<p>Regular Pool Play - The main games of the tournament played on Thursday, Friday and Saturday morning.</p>
<p>
  Play Offs - Quarter finals, semi finals and finals starting Saturday afternoon and continuing through Sunday.
</p>
<p>Jamboree - Warmup games played on Wednesday. Teams are made up on the spot.  Good chance to check out the fields.
  Referees are encouraged to work at whatever level they want.
</p>
<p>EXTRA - I'm not sure of all the details but for the first time the National Games will basically include a second tournament
  involving the AYSO Flex programs.  So basically, we have certain teams competing among themselves.
  I think they are supposed to supply their own referees but I am not sure.  So if you represent an EXTRA team then go ahead and check the
  appropriate level.  If not, then ignore.
</p>
<h3>Which level can I referee?</h3>
<p>
  Start with the National Guidelines: Regional U10, Intermediate U12, Advanced U14, National U16/19.
  Those referees that have put in the time and effort to earn the higher badges will get priority.
  But that is not to say that you can't do the upper divisions with a lower badge.
  Go ahead and indicate which levels you are comfortable with.
  If you plan to referee above your badge level then we will have your Regional Referee Administrator confirm your experience.
</p>