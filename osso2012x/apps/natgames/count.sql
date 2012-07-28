select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 52;

select count(*) from event_person 
left join event on event.id = event_person.event_id where event.project_id = 52 and event_person.person_id IS NOT NULL;

select count(distinct event_person.person_id) 
from event_person left join event on event.id = event_person.event_id where event.project_id = 52;

select count(*) from project_person where project_id = 52;

select event.project_id, event.id as event_id, event.pool, event_person.id as event_person_id, event_person.person_id, event_person.state
from event_person left join event on event.id = event_person.event_id
where project_id = 52 and event_person.person_id is not null and event_person.state is null;

select event.project_id, event.id as game_id, event.pool, 
event_person.id as game_person_id, event_person.person_id, event_person.state, project_person.id as project_person_id
from event_person 
left join event on event.id = event_person.event_id
left join project_person on project_person.person_id = event_person.person_id and project_person.project_id = 52
where event.project_id = 52 and event_person.person_id is not null and project_person.id is null;

; Messed up
; 2061 2187 2198 2308

; Gil F
update event_person set state = 'AssignmentRequested' where id in (5505,5882,5915,6245); // 5885

; Alan V
insert into project_person values(NULL,52,502,'Active',''); // Alan V

; Remove account and person
mysql> select * from account_person where id = 790;
+-----+-----------+------------+----------+--------+------------------+
| id  | person_id | account_id | verified | status | account_relation |
+-----+-----------+------------+----------+--------+------------------+
| 790 |       738 |        689 | No       | Active | Primary          |
+-----+-----------+------------+----------+--------+------------------+
1 row in set (0.00 sec)

mysql> delete from account_person where id = 790;
Query OK, 1 row affected (0.02 sec)

mysql> delete from account where id = 689;
Query OK, 1 row affected (0.03 sec)

mysql> delete from project_person where person_id = 738;
Query OK, 1 row affected (0.12 sec)

mysql> delete from person_registered where person_id = 738;
Query OK, 1 row affected (0.03 sec)

mysql> delete from person where id = 738;
Query OK, 1 row affected (0.03 sec)

mysql>

select person.id,person.first_name,person.last_name 
from event_person 
left join event on event.id = event_person.event_id 
left join person on person.id = event_person.person_id
where event.project_id = 52 and event_person.person_id IS NOT NULL;

select count(distinct event.id) from event 
left join event_team on event.id = event_team.event_id 
left join team on team.id = event_team.team_id 
where event.project_id = 52 and team.type = 'pool';

538  pool play games
1614 slots

212 = 13%
303 = 19%
705 = 43%, 131 referees
 960/1614 = 59%, 170 referees
1230/1614 = 76%, 207 referees

=====================================================================
Two teams swapped brackets
A3 B3

R0095 ID 2192
R0062 ID 2181

update event_team set team_id = 2192 where id in (4812,4825,4830,4817,4832);
update event_team set team_id = 2181 where id in (4889,4892,4897,4898);

update team set key1 = 'U16G PP A3x' where id = 2181;
update team set key1 = 'U16G PP A3'  where id = 2192;
update team set key1 = 'U16G PP B3'  where id = 2181;

update team set desc1 = 'B3 A03T-R0062-U16G'  where id = 2181;
update team set desc1 = 'A3 A03T-R0095-U16G'  where id = 2192;

=======================================================================
Need an interface

update event set timex = '1700' where project_id = 52 and num = 267;
update event set timex = '1700' where project_id = 52 and num = 268;

select id,key1 from project_field where project_id = 52 and key1 IN ('ASH3','ASH4');

update event set field_id = 133 where project_id = 52 and num = 271;
update event set field_id = 136 where project_id = 52 and num = 272;

========================================================================
# Delete games 646
# Reported by 1031
#
# 646	Sat 08:00 AM	TAR5	U16B PP C	 C5 A12A-R0206 Stoermer C6 A14L-R1408 Tucker
# CR:	 Joe Suchoski
# AR 1:	 Pete Angelo
# AR 2:	 Steve Alpert

select id from event where project_id = 52 and num = 646;

# event_id = 2376

delete from event_person where event_id = 2376;
delete from event_team   where event_id = 2376;
delete from event        where       id = 2376;

================================================
update event_person set person_id = null where event_id in (2174,2532,2098,2171);

================================================
531 David Kemp (injured)
589 Tim Reed
727 Neb Rebic
728 Stef Rebic

select event.num, event_person.id as event_person_id, person_id
from event_person
left join event on event.id = event_person.event_id
where datex = '20120707' and timex > '1130' and person_id in (531,589,727,728);

update event_person set person_id = null where event_person.id in (5384,5494,6259,6757);

