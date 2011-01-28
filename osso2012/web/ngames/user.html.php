<?php ?>
<div>
  <table border="1" class="form_table">
    <tr><th colspan="3">Volunteer Information</th></tr>
    <tr><td>.</td><td>User Supplied</td><td>From Eayso</td></tr>

    <tr><td>User Sign In Name</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>AYSO ID</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>AYSO Region</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>First Name</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>Last  Name</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>Nick Name</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>Email</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>Cell Phone</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>Age</td><td><input type="text" name="vi_uname" value="" /></td><td></td></tr>
    <tr><td>AYSO Membership Year</td><td></td><td></td></tr>
    <tr><td>AYSO Referee Badge</td><td></td><td></td></tr>
    <tr><td>AYSO Safe Haven</td><td></td><td></td></tr>

    <tr><td colspan="3">General volunteer information</td></tr>
  </table>
<br />

  <table border="1" class="form_table">
    <tr><th colspan="2">Volunteer Game Plan </th></tr>
    <tr>
      <td>I plan on refereeing during the games</td>
      <td><select name="gp[1]">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>I plan on doing assessments or observations during the games</td>
      <td><select name="gp[1]">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>I plan on coaching or managing a team</td>
      <td><select name="gp[1]">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>I have a player participating in the regular games</td>
      <td><select name="gp[1]">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>I plan on helping on other tasks during the games</td>
      <td><select name="gp[1]">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
      </td>
    </tr>
    <tr><td colspan="2">Overall indication of what you plan on doing at the games</td></tr>
  </table>
<br />
  <table border="1" class="form_table" style="width: 600px;">
    <tr><th colspan="4">Referee Player Relationships</th></tr>
    <tr><td>.</td><td>AYSO Player ID</td><td>Relationship</td><td>Player Name</td></tr>

    <tr>
      <td>Player 1</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td>
        <select>
          <option>Select Relationship</option>
          <option>Child, sibling, family</option>
          <option>Other</option>
        </select>
      </td>
      <td></td>
    </tr>
    <tr>
      <td>Player 2</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td>
        <select>
          <option>Select Relationship</option>
          <option>Child, sibling, family</option>
          <option>Other</option>
        </select>
      </td>
      <td></td>
    </tr>
    <tr><td colspan="4">
        Enter the AYSO player ids for players participating in the tournament.
        Their games will end up being highlighted on your referee schedule.
      </td></tr>
  </table>
<br />
  <table border="1" class="form_table" style="width: 600px;">
    <tr><th colspan="3">Referee Crew Members</th></tr>
    <tr><td>.</td><td>AYSO Referee ID</td><td>Referee Name</td></tr>

    <tr>
      <td>Referee 1</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td></td>
    </tr>
    <tr>
      <td>Referee 2</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td></td>
    </tr>
    <tr>
      <td>Referee 3</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td></td>
    </tr>
    <tr><td colspan="3">
        If you wish to be part of a referee crew then enter the AYSO ids of the other referees.
        The scheduling system will allow one person on the crew to sign up the other members of the crew for games.
      </td></tr>
  </table>
<br />
  <table border="1" class="form_table">
    <tr><th colspan="2">Referee Availability </th></tr>
    <tr>
      <td>Tuesday Night, Opening Ceremony</td>
      <td><select name="ra_tu_oc">
          <option value="0">Do not plan on participating</option>
          <option value="1">Will participate</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Wednesday, Jamboree</td>
      <td><select name="ra_we_ja">
          <option value="0">Not available</option>
          <option value="1">Will referee</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Thursday, Round Robin</td>
      <td><select name="ra_th_rr">
          <option value="0">Not available</option>
          <option value="1">Will referee</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Friday, Round Robin</td>
      <td><select name="ra_fr_rr">
          <option value="0">Not available</option>
          <option value="1">Will referee</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Saturday Morning, Round Robin</td>
      <td><select name="ra_sa_rr">
          <option value="0">Not available</option>
          <option value="1">Will referee</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Sunday Morning, Semi Finals</td>
      <td><select name="ra_su_sf">
          <option value="0">Not available</option>
          <option value="1">Will referee</option>
          <option value="2">Will referee if my team advances</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Sunday Afternoon, Finals</td>
      <td><select name="ra_su_f">
          <option value="0">Not available</option>
          <option value="1">Will referee</option>
          <option value="1">Will referee if my team advances</option>
        </select>
      </td>
    </tr>
    <tr><td colspan="2">Indicate all time slots for which you plan on being available</td></tr>
  </table>
<br />
  <table border="1" class="form_table" style="width: 500px;">
    <tr><th colspan="7">Age Groups to Referee</th></tr>
    <tr>
      <td></td>
      <td>NA</td>
      <td>U10</td>
      <td>U12</td>
      <td>U14</td>
      <td>U16</td>
      <td>U19</td>
    </tr>
    <tr>
      <td>Center Referee - Boys</td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[2]" value="0" /></td>
      <td><input type="checkbox" name="rl[3]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
     </tr>
    <tr>
      <td>Center Referee - Girls</td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[2]" value="0" /></td>
      <td><input type="checkbox" name="rl[3]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
     </tr>
    <tr>
      <td>Assistant Referee - Boys</td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[2]" value="0" /></td>
      <td><input type="checkbox" name="rl[3]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
     </tr>
    <tr>
      <td>Assistant Referee - Girls</td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[2]" value="0" /></td>
      <td><input type="checkbox" name="rl[3]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
     </tr>
    <tr><td colspan="7">
        Indicate which age/gender groups you plan to referee.
        By default, you will be limited to divisions based on your AYSO Referee badge.
        Region U10, Intermediate U12, Advanced U14, National U16/19.
        However, with permission of your referee administrator, you will be able to ref the older games as well.
      </td></tr>
  </table>
<br />
  <table border="1" class="form_table" style="width: 500px;">
    <tr><th colspan="8">Referee Lodging Request </th></tr>
    <tr>
      <td>Sun</td>
      <td>Mon</td>
      <td>Tue</td>
      <td>Wed</td>
      <td>Thu</td>
      <td>Fri</td>
      <td>Sat</td>
      <td>Sun</td>
    </tr>
    <tr>
      <td><input type="checkbox" name="rl[1]" value="0" /></td>
      <td><input type="checkbox" name="rl[2]" value="0" /></td>
      <td><input type="checkbox" name="rl[3]" value="0" /></td>
      <td><input type="checkbox" name="rl[4]" value="0" /></td>
      <td><input type="checkbox" name="rl[5]" value="0" /></td>
      <td><input type="checkbox" name="rl[6]" value="0" /></td>
      <td><input type="checkbox" name="rl[7]" value="0" /></td>
      <td><input type="checkbox" name="rl[8]" value="0" /></td>
    </tr>
    <tr><td colspan="8">
        No promises but we hope to provide low cost lodging in the form of dorm rooms for referees
        and other support volunteers.  Shuttle service to and from the fields will also be provided.
        Indicate which nights you will need a room for.
      </td></tr>
  </table>
<br />
  <table border="1" class="form_table">
    <tr><th colspan="4">Referee Certifications</th></tr>
    <tr>
      <td>Organization</td>
      <td>Identification Number</td>
      <td>Grade</td>
      <td>Years Exp</td>
    </tr>

    <tr>
      <td>AYSO Referee</td><td><input type="text" name="vi_uname" value="" /></td>
      <td>
        <select>
          <option value="Reg">Select Badge Level</option>
          <option value="Reg">Regional </option>
          <option value="Reg">Intermediate</option>
          <option value="Reg">Advanced</option>
          <option value="Reg">National</option>
          <option value="Reg">National 1</option>
          <option value="Reg">National 2</option>
          <option value="Reg">Assistant</option>
        </select>
      </td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>

    <tr>
      <td>AYSO Assessor</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td>
        <select>
          <option value="Reg">Select Badge Level</option>
          <option value="Reg">Regular </option>
          <option value="Reg">National</option>
        </select>
      </td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>

    <tr>
      <td>USSF Referee</td><td><input type="text" name="vi_uname" value="" /></td>
      <td>
        <select>
          <option value="Reg">Select Badge Level</option>
          <option value="Reg">Grade 9</option>
          <option value="Reg">Grade 8</option>
          <option value="Reg">Grade 7</option>
          <option value="Reg">Grade 6</option>
          <option value="Reg">Grade 5</option>
          <option value="Reg">Grade 4</option>
          <option value="Reg">Grade 3</option>
          <option value="Reg">Grade 2</option>
          <option value="Reg">Grade 1</option>
        </select>
      </td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>

    <tr>
      <td>USSF Assessor</td><td><input type="text" name="vi_uname" value="" /></td>
      <td>
        <select>
          <option value="Reg">Select Badge Level</option>
          <option value="Reg">Regular </option>
          <option value="Reg">National</option>
        </select>
      </td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>

    <tr>
      <td>NFHS (High School) Referee</td><td><input type="text" name="vi_uname" value="" /></td>
      <td>
       <select>
          <option value="Reg">Select Badge Level</option>
          <option value="Reg">Class 1</option>
          <option value="Reg">Class 2</option>
          <option value="Reg">Class 3</option>
          <option value="Reg">Class 4</option>
        </select>
      </td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>

    <tr>
      <td>NISOA (College) Referee</td><td><input type="text" name="vi_uname" value="" /></td>
      <td>
       <select>
          <option value="Reg">Select Badge Level</option>
          <option value="Reg">Class 1</option>
          <option value="Reg">Class 2</option>
          <option value="Reg">Class 3</option>
          <option value="Reg">Class 4</option>
        </select>
      </td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>
    
    <tr>
      <td>Other Referee</td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td><input type="text" name="vi_uname" value="" /></td>
      <td><input type="text" name="rc_exp" size="2" value="" /></td>
    </tr>

    <tr><td colspan="4">
        Indicate which other referee certifications you have.  This will help us to ensure you are placed on suitable games.
      </td></tr>
  </table>

    
</div>