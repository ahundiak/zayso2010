<?php
  $data = $this->data;
  $ages = array('U10','U12','U14','U16','U19');
?>
<form method="post" action="projinfo-plans">
  <table border="1" class="form_table" width="600" id="plans-plans">
    <tr><th colspan="2">Volunteer Tournament Plans</th></tr>
    <tr>
      <td>I plan on attending the games</td>
      <td><?php echo $this->genSelect('attend'); ?></td>
    </tr>
    <tr><td colspan="2">Answer the rest of the questions if you plan on attending.</td></tr>
    <tr>
      <td>I plan on refereeing during the games</td>
      <td><?php echo $this->genSelect('will_referee'); ?></td>
    </tr>
    <tr>
      <td>I plan on doing assessments or observations during the games</td>
      <td><?php echo $this->genSelect('do_assessments'); ?></td>
    </tr>
    <tr>
      <td>I would like to have an assessment or observation</td>
      <td><?php echo $this->genSelect('want_assessment'); ?></td>
    </tr>
    <tr>
      <td>I plan on coaching or managing a team</td>
      <td><?php echo $this->genSelect('coaching'); ?></td>
    </tr>
    <tr>
      <td>I have a player participating in the games</td>
      <td><?php echo $this->genSelect('have_player'); ?></td>
    </tr>
    <tr>
      <td>I plan on helping on other tasks during the games</td>
      <td><?php echo $this->genSelect('other_jobs'); ?></td>
    </tr>
    <tr>
      <td colspan="1">Press Update to save your answers</td>
      <td align="right" colspan="1"><input type="submit" name="plans_submit_plans"  value="Update" />
    </tr>
    <tr><td colspan="2">Overall indication of what you plan on doing at the games</td></tr>
  </table>
<br />
  <table border="1" class="form_table" width="600"  id="plans-avail">
    <tr><th colspan="2">Referee Availability </th></tr>
    <tr>
      <td>Tuesday Night, Opening Ceremony</td>
      <td><?php echo $this->genSelect('attend_open'); ?></td>
    </tr>
    <tr>
      <td>Wednesday, Jamboree</td>
      <td><?php echo $this->genSelect('avail_wed'); ?></td>
    </tr>
    <tr>
      <td>Thursday, Pool Play</td>
      <td><?php echo $this->genSelect('avail_thu'); ?></td>
    </tr>
    <tr>
      <td>Friday, Pool Play</td>
      <td><?php echo $this->genSelect('avail_fri'); ?></td>
    </tr>
    <tr>
      <td>Saturday Morning, Pool Play</td>
      <td><?php echo $this->genSelect('avail_sat_morn'); ?></td>
    </tr>
    <tr>
      <td>Saturday Afternoon, Quarter Finals</td>
      <td><?php echo $this->genSelect('avail_sat_after'); ?></td>
    </tr>
    <tr>
      <td>Sunday Morning, Semi Finals</td>
      <td><?php echo $this->genSelect('avail_sun_morn'); ?></td>
    </tr>
    <tr>
      <td>Sunday Afternoon, Finals</td>
      <td><?php echo $this->genSelect('avail_sun_after'); ?></td>
    </tr>
    <tr>
      <td colspan="1">Press Update to save your answers</td>
      <td align="right" colspan="1"><input type="submit" name="plans_submit_avail"  value="Update" />
    </tr>
    <tr><td colspan="2">Indicate all time slots for which you plan on being available</td></tr>
  </table>
<br />
  <table border="1" class="form_table" width="600"  id="plans-lodging">
    <tr><th colspan="8">Referee Lodging Request </th></tr>
    <tr>
      <td align="center">Sun</td>
      <td align="center">Mon</td>
      <td align="center">Tue</td>
      <td align="center">Wed</td>
      <td align="center">Thu</td>
      <td align="center">Fri</td>
      <td align="center">Sat</td>
      <td align="center">Sun</td>
    </tr>
    <tr>
      <td align="center"><?php echo $this->genCheckBox('room_sun0'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_mon0'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_tue1'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_wed1'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_thu1'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_fri1'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_sat1'); ?></td>
      <td align="center"><?php echo $this->genCheckBox('room_sun1'); ?></td>
    </tr>
  </table>
  <table border="1" class="form_table" width="600">
    <tr>
      <td>Name of Room Mate 1</td>
      <td><?php echo $this->genRoomMateName('room_mate1'); ?></td>
    </tr>
     <tr>
      <td>Name of Room Mate 2</td>
      <td><?php echo $this->genRoomMateName('room_mate2'); ?></td>
    </tr>
     <tr>
      <td>Name of Room Mate 3</td>
      <td><?php echo $this->genRoomMateName('room_mate3'); ?></td>
    </tr>
    <tr>
      <td colspan="1">Press Update to save your answers</td>
      <td align="right" colspan="1"><input type="submit" name="plans_submit_lodging" value="Update" />
    </tr>
    <tr><td colspan="2">
        No promises but we hope to provide low cost lodging in the form of dorm rooms for referees
        and other support volunteers.  Shuttle service to and from the fields will also (hopefully) be provided.
        Indicate which nights you will need a room for.
        If you plan on rooming with other referees, then please enter their names.
      </td></tr>
  </table>
<br />
</form>
<h3>Notes</h3>
<p>Please try to keep these plans up to date as tournament time approaches.</p>
<p>When you are done answering these questions, press Update then <a href="home">Click on Home.</a></p>
