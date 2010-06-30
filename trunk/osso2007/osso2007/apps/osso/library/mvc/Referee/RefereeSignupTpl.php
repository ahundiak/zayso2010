<?php $event = $this->event; ?>

<form method="post" action="<?php echo $this->link('sched_ref_list'); ?>">

<table border="1">
<tr><th colspan="2">Referee Signup</th></tr>
<tr>
    <td>Game Number</td>
    <td><?php echo $event->id; ?></td>
    <input type="hidden" name="referee_signup_event_id" value="<?php echo $event->id; ?>" />
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
<?php foreach($event->teams as $team) { ?>
<tr>
    <td>Team <?php echo $this->escape($team->eventTeamTypeDesc); ?></td>
    <td>     <?php echo $this->escape($team->schedDesc); ?></td>
</tr>
<?php } ?>
<?php foreach($this->eventPersons as $eventPerson) { ?>
<tr>
    <td><?php echo $eventPerson->personTypeDesc; ?></td>
    <td>
        <select name="referee_signup_event_persons[]" />
          <?php echo $this->formOptions($eventPerson->personPickList,$eventPerson->personId); ?>" />
        </select>
    </td>
</tr>
<?php } ?>
<tr>
    <td><?php echo $this->href('Referee Schedule','sched_ref_list'); ?></td>
    <td><input type="submit" name="referee_signup_submit" value="Signup" /></td>
</tr>
</table></form>

