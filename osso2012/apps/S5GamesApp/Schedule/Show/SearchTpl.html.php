<?php
  $user   = $this->services->user;
  $data   = $this->data;
  $search = $data;
?>
<form name="sched_search" method="post" action="schedule-show" >

<input type="hidden" name="sched_show_fri"  value="0" />
<input type="hidden" name="sched_show_sat"  value="0" />
<input type="hidden" name="sched_show_sun"  value="0" />
<input type="hidden" name="sched_show_u10"  value="0" />
<input type="hidden" name="sched_show_u12"  value="0" />
<input type="hidden" name="sched_show_u14"  value="0" />
<input type="hidden" name="sched_show_u16"  value="0" />
<input type="hidden" name="sched_show_u19"  value="0" />
<input type="hidden" name="sched_show_coed" value="0" />
<input type="hidden" name="sched_show_girl" value="0" />
<input type="hidden" name="sched_show_mm"   value="0" />
<input type="hidden" name="sched_show_jh"   value="0" />

<table border="1" width="800">
<tr><th colspan="3">Schedule Search Form</th></tr>
<tr>
  <td>Show</td>
  <td>
    <table border = "1">
      <tr>
        <td>Fri</td>
        <td>Sat</td>
        <td>Sun</td>
        <td width="10">&nbsp;</td>
        <td>U10</td>
        <td>U12</td>
        <td>U14</td>
        <td>U16</td>
        <td>U19</td>
        <td width="10">&nbsp;</td>
        <td>Boys</td>
        <td>Girl</td>
      </tr>
      <tr>
        <td><?php echo $this->formCheckBox('sched_show_fri', $search->showFri); ?></td>
        <td><?php echo $this->formCheckBox('sched_show_sat', $search->showSat); ?></td>
      	<td><?php echo $this->formCheckBox('sched_show_sun', $search->showSun); ?></td>
        <td>.</td>
      	<td><?php echo $this->formCheckBox('sched_show_u10', $search->showU10); ?></td>
      	<td><?php echo $this->formCheckBox('sched_show_u12', $search->showU12); ?></td>
      	<td><?php echo $this->formCheckBox('sched_show_u14', $search->showU14); ?></td>
      	<td><?php echo $this->formCheckBox('sched_show_u16', $search->showU16); ?></td>
      	<td><?php echo $this->formCheckBox('sched_show_u19', $search->showU19); ?></td>
        <td>.</td>
      	<td><?php echo $this->formCheckBox('sched_show_coed',$search->showCoed);?></td>
      	<td><?php echo $this->formCheckBox('sched_show_girl',$search->showGirl);?></td>
      </tr>
<?php /*
      <tr>
        <td colspan="4">Merrimack: <?php echo $this->formCheckBox('sched_show_mm', $tpl->showMM); ?></td>
        <td colspan="4">John Hunt: <?php echo $this->formCheckBox('sched_show_jh', $tpl->showJH); ?></td>
      </tr>
 *
 */ ?>
    </table>
  </td>
  <td>
    <table>
      <tr>
        <td>Sort By</td>
        <td>
          <select name="sched_sort">
            <?php echo $this->formOptions($data->sortPickList,$search->sort); ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Coach Search</td>
        <td>
          <input type="text" name="sched_search_coach" size="20"
                 value="<?php echo $this->escape($search->coach); ?>" />
        </td>
      </tr>
      <tr>
        <td>Referee Search</td>
        <td>
          <input type="text" name="sched_search_referee" size="20"
                 value="<?php echo $this->escape($search->referee); ?>" />
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td colspan="2">
    User: <?php echo $this->escape($user->getName()); ?>
  </td>
  <td>
    <table>
      <tr>
        <td>
          <a href="account-logout">Logout</a>
          <a href="schedule-show/excel">Excel</a>
          <a href="schedule-show/csv">CSV</a>
        </td>
        <td align="right">
          <input type="submit" name="submit" value="Search"/>
        </td>
      </tr>
    </table>
  </td>
</tr>
<?php /*
<tr>
	<td colspan="3">
        <?php if (MYAPP_CONFIG_DISABLE_SIGNUPS) { ?>
	<span style="background-color: red">Online signup is disabled, please email or call to signup.</span><br />
        <?php } ?>
	Signup for games by clicking on one of the referee links in the right hand column.
	If you don't see any links then make sure you are logged in with the correct password.<br />
	Green referee names are pending approval, Black means approved.  
	Red means the referee really does not want to do the game so feel free to take the assignment.
	Brown are the ones either assigned or changed by me.<br />
	Fields: <a href="http://ayso160.clubspaces.com/PageCustom.aspx?id=4&o=19897">JH = John Hunt Park, MM = Merrimack Park.</a><br />
        <?php echo "Game Coverage: {$tpl->statCovered}%, Open Slots: {$tpl->statOpenSlots}\n"; ?><br />
	Contact me if you need to remove yourself from a game or you want a slot that has already been taken.<br>
	For help: Contact <a href="mailto:ahundiak@gmail.com">Art Hundiak</a>. Phone: 256.457.5943
	</td>
</tr>
 *
 */ ?>
</table>

<br />
