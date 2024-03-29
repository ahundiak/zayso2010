<?php $data = $this->schedRefListData; ?>

<form method="post" action="<?php echo $this->link('sched_ref_list'); ?>">
<table border="1" style="width: 700px">
<tr><th colspan="2">Show Referee Schedules</th></tr>
<tr>
    <td colspan="2">
        <select name="sched_div_season_type_id">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>
        </select>
        <select name="sched_div_year_id">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>
        </select>
        <select name="sched_div_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>
        </select>
        Order By
        <select name="sched_div_order_by">
            <?php echo $this->formOptions($this->orderByPickList,$data->orderBy); ?>
        </select>
        Output
        <input type="submit" name="sched_ref_submit_spreadsheet" value="Spreadsheet" />
<?php /*
        <select name="sched_div_output_type">
            <?php echo $this->formOptions($this->outputTypePickList,$data->outputType); ?>
        </select>
 */ ?>
    </td>
</tr>
<tr><td colspan="2"></td></tr>
<tr>
    <td colspan="2">
        <table border="1">
        <tr>
            <td>From</td>
            <td>
                <select name="sched_div_date_year1">
                    <?php echo $this->formOptions($this->dateYearPickList,$data->dateYear1); ?>
                </select>
                <select name="sched_div_date_month1">
                    <?php echo $this->formOptions($this->dateMonthPickList,$data->dateMonth1); ?>
                </select>
                <select name="sched_div_date_day1">
                    <?php echo $this->formOptions($this->dateDayPickList,$data->dateDay1); ?>
                </select>
            </td>
            <td>
                <?php echo $this->formatDate($data->date1); ?>
            </td>
            <td>Division
                <select name="sched_div_show_age1">
                    <option value="-1">Same</option>
                    <?php echo $this->formOptions($this->divisionAgePickList,$data->showAge1); ?>
                </select>
            </td>
            <td>
                Boy <?php echo $this->formCheckBox('sched_div_show_boy', $data->showBoy); ?>
                Girl<?php echo $this->formCheckBox('sched_div_show_girl',$data->showGirl); ?>
                Coed<?php echo $this->formCheckBox('sched_div_show_coed',$data->showCoed); ?>
            </td>
        </tr>
        <tr>
            <td>To</td>
            <td>
                <select name="sched_div_date_year2">
                    <?php echo $this->formOptions($this->dateYearPickList,$data->dateYear2); ?>
                </select>
                <select name="sched_div_date_month2">
                    <?php echo $this->formOptions($this->dateMonthPickList,$data->dateMonth2); ?>
                </select>
                <select name="sched_div_date_day2">
                    <?php echo $this->formOptions($this->dateDayPickList,$data->dateDay2); ?>
                </select>
            </td>
            <td>
                <?php echo $this->formatDate($data->date2); ?>
            </td>
            <td>Division
                <select name="sched_div_show_age2">
                    <option value="-1">Same</option>
                    <?php echo $this->formOptions($this->divisionAgePickList,$data->showAge2); ?>
                </select>
            </td>
            <td>
                Home<?php echo $this->formCheckBox('sched_div_show_home',$data->showHome); ?>
                Away<?php echo $this->formCheckBox('sched_div_show_away',$data->showAway); ?>
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td>
        Evt Type
        <select name="sched_div_event_type_id">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->eventTypePickList,$data->eventTypeId); ?>
        </select>
        Sch Type
        <select name="sched_div_schedule_type_id">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->scheduleTypePickList,$data->scheduleTypeId); ?>
        </select>
        Proc
        <select name="event_point2">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->eventPoint2PickList,$data->point2); ?>
        </select>
    </td>
    <td style="text-align: right">
        <input type="submit" name="sched_div_submit_search" value="Search" />
    </td>
</tr>
<tr>
	<td>
        Show For Referee
        <select name="sched_div_show">
            <?php echo $this->formOptions($this->showPickList,$data->show); ?>
        </select>
	</td>
	<td style="text-align: center">
	<?php echo count($this->events); ?>
	</td>
</tr>
	
</table>
</form>
<br />
<table border="1">
<tr><th colspan="6">Referee Schedule</th></tr>
<tr>
    <td>Event</td>
    <td>Date</td>
    <td>Time</td>
    <td>Location</td>
    <td>Teams (Home/Away)</td>
    <td style="width: 250px;">Referees</td>
</tr>
<?php 
    $odd = FALSE;
    foreach($this->events as $event) 
    {
    	if ($this->showEvent($event)) {
    	 
        	if ($odd) $odd = FALSE;
        	else      $odd = TRUE;
        	
        	$eventContent = $event->eventTypeDesc . ' ' . $event->id;
//        	if ($event->num) $eventContent .= '-' . $event->num;
        
        	$eventDesc = $this->href($this->escape($eventContent),'event_edit',$event->id);;

        	if ($event->scheduleTypeId == 2) $eventDesc .= '<br />REG TOURN';
        	if ($event->scheduleTypeId == 3) $eventDesc .= '<br />AREA TOURN';
        	
        	//$eventDesc = $this->href($this->escape($event->eventTypeDesc . ' ' . $event->id),'event_edit',$event->id);;
        	//if ($event->scheduleTypeId != 1) $eventDesc .= '<br />TOURNAMENT';
 ?>
<tr>
    <td><?php echo $eventDesc; ?></td>
    <td><?php echo $this->formatDate($event->date);  ?></td>
    <td><?php echo $this->formatTime($event->time);  ?></td>
    <td><?php echo $this->escape($event->fieldDesc); ?></td>
    <td>
        <?php echo $this->displayTeams($event); ?>
    </td>
    <td>
        <?php echo $this->displayPersons($event); ?>
    </td>
</tr>
<?php }} ?>
</table>

