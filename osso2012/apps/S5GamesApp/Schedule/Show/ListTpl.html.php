<?php
  $user  = $this->services->user;
  $data  = $this->data;
  $games = $data->games;
  $gameNums = $data->gameNums;
?>

<table border = "1" width="800">
<tr><th colspan="8">Game and Referee Schedule <?php echo count($games); ?></th></tr>
<tr>
  <td width="45">Game</td>
  <td width="45">Date</td>
  <td width="45">Time</td>
  <td width="80">Field</td>
  <td width="40">Div</td>
  <td width="70">Bracket</td>
  <td width="160">Home/Away Team</td>
  <td width="250">Referees</td>
</tr>
<?php
  foreach($games as $gameItem)
  {
    $game = $this->getGameDisplay($gameItem);
    switch($game->id)
    {
      case 666:
        $style = 'style="background-color: red"';
	break;
      default:
        $style = NULL;
    }
?>
<tr <?php echo $style; ?> >
  <td align="center">
    <?php echo $game->id; ?> <br />
    <?php
      $selected = null;
      if ($gameNums && isset($gameNums[$game->id])) $selected = 'checked="checked"';
      if ($user->isAdmin()) {
    ?>
    <input 
        type="checkbox"
        name = "game_nums[<?php echo $game->id; ?>]"
        value="<?php echo $game->id; ?>"
        <?php echo $selected; ?>
        />
    <?php } ?>
  </td>
  <td><?php echo $game->date;    ?></td>
  <td><?php echo $game->time;    ?></td>
  <td><?php echo $game->field;   ?></td>
  <td><?php echo $game->div;     ?></td>
  <td><?php echo $game->bracket; ?></td>
  <td><?php echo $game->teams;   ?></td>
  <td><?php echo $game->persons; ?></td>
</tr>
<?php } ?>
</table>
</form>