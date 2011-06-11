<?php
  $regions  = $this->regions;
  $referees = $this->referees;

  $gameCount = $this->gameCount;
  $gameSlots = $gameCount*3;
  $slotCount = $this->slotCount;
  $covered = (int)(($slotCount / $gameSlots) * 100);
  
  $stats = "Games $gameCount, Slots $gameSlots, Covered $slotCount - $covered%";
?>
<div>
<table border = "1">
<tr><th colspan="5">Schedule Statistics - <?php echo $stats; ?></th></tr>
<tr>
  <td width="50">Region</td>
  <td width="50">Teams</td>
  <td width="50">Games</td>
  <td width="50">Slot Quota-Covered</td>
  <td width="50">Num Referees</td>
</tr>
<?php
  foreach($regions as $region)
  {
?>
<tr>
  <td><?php echo $region['region'];     ?></td>
  <td><?php echo $region['team_count']; ?></td>
  <td><?php echo $region['game_count']; ?></td>
  <td><?php echo (int)(($region['game_count'] * 3) / 2) . '-' . $region['slot_count']; ?></td>
  <td><?php echo $region['ref_count']; ?></td>
</tr>
<?php } ?>
</table>
<br />
<table border = "1">
<tr><th colspan="2">Referees</th></tr>
<tr>
  <td width="200">Referee</td>
  <td width="50">Slots</td>
</tr>
<?php
  foreach($referees as $referee)
  {
?>
<tr>
  <td><?php echo $referee['ref_key'];    ?></td>
  <td><?php echo $referee['slot_count']; ?></td>
</tr>
<?php } ?>
</table>
</div>

