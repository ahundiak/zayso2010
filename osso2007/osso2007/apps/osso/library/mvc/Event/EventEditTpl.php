<?php $event = $this->event; ?>

<form method="post" action="<?php echo $this->link('event_edit'); ?>"> 
<input type="hidden" name="event_id"         value="<?php echo $event->id; ?>" />
<input type="hidden" name="event_num"        value="<?php echo $event->num; ?>" />
<input type="hidden" name="event_project_ix" value="<?php echo $event->projectId; ?>" />
<input type="hidden" name="event_point1"     value="<?php echo $event->point1; ?>" />

<input type="hidden" name="event_season_type_id"    value="<?php echo $event->seasonTypeId; ?>" />
<input type="hidden" name="event_year_id"           value="<?php echo $event->yearTypeId; ?>" />
<input type="hidden" name="event_schedule_type_id"  value="<?php echo $event->scheduleTypeId; ?>" />

<table border="1" style="width: 875px">
<tr>
    <th colspan="2">
        <?php
            $ts = $this->formatDate($event->date) . ' ' . $this->formatTime($event->time);
            if ($event->id) echo "Edit Event - {$event->id} - {$ts}";
            else            echo "Create New Event - {$ts}";
        ?>
    </th>
</tr>
<tr>
    <td>Type</td>
    <td colspan="1">
        <select name="event_project_id">
            <?php echo $this->formOptions($this->projectPickList,$event->projectId); ?>
        </select>
        <select name="event_event_type_id">
            <?php echo $this->formOptions($this->eventTypePickList,$event->eventTypeId); ?>
        </select>
        Boy <?php echo $this->formCheckBox('event_show_boy', $this->showBoy); ?>
        Girl<?php echo $this->formCheckBox('event_show_girl',$this->showGirl); ?>
        Coed<?php echo $this->formCheckBox('event_show_coed',$this->showCoed); ?>
    </td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
    <td>Date-Time</td>
    <td colspan="1">
        <select name="event_date_month">
            <?php echo $this->formOptions($this->dateMonthPickList,substr($event->date,4,2)); ?>
        </select>
        <select name="event_date_day">
            <?php echo $this->formOptions($this->dateDayPickList,substr($event->date,6,2)); ?>
        </select>
        <select name="event_date_year">
            <?php echo $this->formOptions($this->dateYearPickList,substr($event->date,0,4)); ?>
        </select>
        -
        <select name="event_date_hour">
            <?php echo $this->formOptions($this->dateHourPickList,substr($event->time,0,2)); ?>
        </select>
        <select name="event_date_minute">
            <?php echo $this->formOptions($this->dateMinutePickList,substr($event->time,2,2)); ?>
        </select>
        - Field Duration
         <select name="event_date_duration">
            <?php echo $this->formOptions($this->dateDurationPickList,$event->duration); ?>
        </select>
    </td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
    <td>Processing State</td>
    <td colspan="1">
        <select name="event_point2">
            <?php echo $this->formOptions($this->eventPoint2PickList,$event->point2); ?>
        </select>
     </td>
</tr>
<tr>
    <td>Administrator</td>
    <td colspan="1">
        <select name="event_unit_id">
            <option value="-1">Same As Home Team</option>
            <?php echo $this->formOptions($this->unitPickList,$event->unitId); ?>
        </select>
     </td>
</tr><tr>
    <td>Location</td>
    <td colspan="1">
        <select name="event_field_unit_id">
            <option value="-1">Same As Home Team</option>
            <?php echo $this->formOptions($this->unitPickList,$event->fieldUnitId); ?>
        </select>
        Field Site
        <select name="event_field_site_id">
            <?php echo $this->formOptions($this->fieldSitePickList,$event->fieldSiteId); ?>
            <option value="0">To Be Determined</option>
        </select>
        Field
        <select name="event_field_id">
            <?php echo $this->formOptions($this->fieldPickList,$event->fieldId); ?>
            <option value="0">To Be Determined</option>
        </select>
     </td>
</tr>
<tr><td colspan="2"></td></tr>
<?php
    $eventGameTeamTypes = array(
        $this->context->models->EventTeam->typeHome,
        $this->context->models->EventTeam->typeAway,
    );
    $eventTypeGame = $this->context->models->Event->typeGame;
    foreach($this->eventTeams as $eventTeam) { 
        $eventTeamId = $eventTeam->id;
        //if (($event->eventTypeId != $eventTypeGame) || in_array($eventTeam->eventTeamTypeId,$eventGameTeamTypes)) { 
?>
<tr>
    <input type="hidden" name="event_team_ids[<?php echo $eventTeamId; ?>]"         value="<?php echo $eventTeamId; ?>" />
    <input type="hidden" name="event_team_year_ids[<?php echo $eventTeamId; ?>]"    value="<?php echo $eventTeam->yearId; ?>" />
    <input type="hidden" name="event_team_type_ids[<?php echo $eventTeamId; ?>]"    value="<?php echo $eventTeam->eventTeamTypeId; ?>" />
    <input type="hidden" name="event_team_type_descs[<?php echo $eventTeamId; ?>]"  value="<?php echo $eventTeam->eventTeamTypeDesc; ?>" />
    <input type="hidden" name="event_team_event_ids[<?php echo $eventTeamId; ?>]"   value="<?php echo $eventTeam->eventId; ?>" />
    <td>Team <?php echo $this->escape($eventTeam->eventTeamTypeDesc); ?></td>
    <td colspan="1">
        <select name="event_team_unit_ids[<?php echo $eventTeamId; ?>]">
            <?php if ($eventTeam->eventTeamTypeId != EventTeamTypeModel::TYPE_HOME) { ?>
            <option value="-1">Same As Home Team</option>
            <?php } ?>
            <?php echo $this->formOptions($this->unitPickList,$eventTeam->unitId); ?>
        </select>
        Division
        <select name="event_team_division_ids[<?php echo $eventTeamId; ?>]">
            <?php if ($eventTeam->eventTeamTypeId != EventTeamTypeModel::TYPE_HOME) { ?>
            <option value="-1">Same</option>
            <?php } ?>
            <?php echo $this->formOptions($this->divisionPickList,$eventTeam->divisionId); ?>
        </select>
        Team
        <select name="event_team_sch_team_ids[<?php echo $eventTeamId; ?>]">
            <option value="0">To Be Determined</option>
            <?php echo $this->formOptions($eventTeam->schTeamPickList,$eventTeam->schTeamId); ?>
        </select>
    
    </td>
</tr>
<?php } ?>
<tr><td colspan="2"></td></tr>
<tr>
    <td>
        <?php echo $this->href('Division Schedule','sched_div_list'); ?>
        <br />
        <?php echo $this->href('Referee  Schedule','sched_ref_list'); ?>
    </td>
    <td style="text-align: right">
        <?php echo $this->formUDC('event',$event->id); ?>
        <input type="submit" name="event_submit_refresh" value="Refresh Team and Field Pick Lists" />
    </td>
</tr>
</table>
</form>
