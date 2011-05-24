<?php
  $games = $this->games;
?>
<div>
  <table border="1">
    <tr><th colspan="14">Arbiter Game Schedule</th></tr>
    <?php foreach($games AS $game) { ?>
    <tr>
      <td><?php echo $game->getGameNum();  ?></td>
      <td><?php echo $game->getDate();     ?></td>
      <td><?php echo $game->getDow();      ?></td>
      <td><?php echo $game->getTime();     ?></td>
      <td><?php echo $game->getSport();    ?></td>
      <td><?php echo $game->getLevel();    ?></td>
      <td><?php echo $game->getSite();     ?></td>
      <td><?php echo $game->getHomeTeam(); ?></td>
      <td><?php echo $game->getAwayTeam(); ?></td>
      <td><?php echo $game->getHomeScore();?></td>
      <td><?php echo $game->getAwayScore();?></td>
      <td><?php echo $game->getCR();       ?></td>
      <td><?php echo $game->getAR1();      ?></td>
      <td><?php echo $game->getAR2();      ?></td>
    </tr>
    <?php } ?>
  </table>

  <p>Arbiter Schedule</p>
</div>

