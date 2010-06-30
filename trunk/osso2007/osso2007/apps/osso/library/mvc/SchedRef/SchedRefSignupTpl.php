<?php $event = $this->event; ?>

<form method="post" action="<?php echo $this->link('sched_ref_signup'); ?>">

<table border="1">
<tr><th colspan="2">Referee Signup</th></tr>
<tr>
    <td>Game Number</td>
    <td><?php echo $event->id; ?></td>
    <input type="hidden" name="referee_signup_event_id" value="<?php echo $event->id; ?>" />
</tr>
<tr>
    <td>Process State</td>
    <?php if (!$this->user->isAdmin) { ?>
        <td><?php echo $this->eventPoint2PickList[$event->point2]; ?></td>
        <input type="hidden" name="event_point2" value="<?php echo $event->point2; ?>" />
    <?php } else { ?>
    <td>
        <select name="event_point2" />
          <?php echo $this->formOptions($this->eventPoint2PickList,$event->point2); ?>" />
        </select>
    </td>
    <?php } ?>
</tr>
<tr>
    <td>Location</td>
    <td><?php echo $this->escape($event->fieldDesc); ?></td>
</tr>
<tr>
    <td>Date</td>
    <td><?php echo $this->formatDate($event->date);  ?></td>
</tr>
<tr>
    <td>Time</td>
    <td><?php echo $this->formatTime($event->time);  ?></td>
</tr>
<?php $teams = $event->teams; foreach($teams as $team) { ?>
<tr>
    <td>Team <?php echo $this->escape($team->eventTeamTypeDesc); ?></td>
    <td>     <?php echo $this->escape($team->schedDesc); ?></td>
</tr>
<?php } ?>
<?php foreach($this->eventPersons as $eventPerson) { $eventPersonId = $eventPerson->id; ?>
<input type="hidden" name="referee_signup_event_person_ids[<?php echo $eventPersonId; ?>]" value="<?php echo $eventPersonId; ?>" />
<input type="hidden" name="referee_signup_event_person_type_ids[<?php echo $eventPersonId; ?>]" value="<?php echo $eventPerson->personTypeId; ?>" />
<tr>
    <td><?php echo $eventPerson->personTypeDesc; ?></td>
    <td>
        <select name="referee_signup_event_person_person_ids[<?php echo $eventPersonId; ?>]" />
          <?php echo $this->formOptions($eventPerson->personPickList,$eventPerson->personId); ?>" />
        </select>
    </td>
</tr>
<?php } ?>
<tr>
    <td><?php echo $this->href('Referee Schedule','sched_ref_list'); ?></td>
    <td><input type="submit" name="referee_signup_submit" value="Signup" 
    <?php // if (!$this->context->user->isAdmin) echo 'disabled="disabled"';?> /></td>
</tr>
<?php /* ?>
<tr>
  <td colspan="2">Referee sign ups have been disabled as of NOON, Friday, Nov 13th.  <br />
  Please contact Art Hundiak, ahundiak@ayso894.net, 256.457.5943 in order to sign up for a game.
  </td>
</tr>
<?php */ ?>
</table></form>

