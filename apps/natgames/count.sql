select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 52;

select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 52 and event_person.person_id IS NOT NULL;

select count(distinct event_person.person_id) from event_person left join event on event.id = event_person.event_id where event.project_id = 52;

select count(*) from project_person where project_id = 52;

select event.project_id, event.id, event.pool, event_person.id,event_person.person_id, event_person.state
from event_person left join event on event.id = event_person.event_id
where project_id = 52 and event_person.person_id is not null and event_person.state is null;

select event.project_id, event.id as game_id, event.pool, 
event_person.id as game_person_id, event_person.person_id, event_person.state, project_person.id as project_person_id
from event_person 
left join event on event.id = event_person.event_id
left join project_person on project_person.person_id = event_person.person_id and project_person.project_id = 52
where event.project_id = 52 and event_person.person_id is not null and project_person.id is null;

update event_person set state = 'AssignmentRequested' where
id in ();

; Alan V
insert into project_person values(NULL,62,499,'Active','');
insert into project_person values(NULL,62,500,'Active','');
insert into project_person values(NULL,62,501,'Active','');
insert into project_person values(NULL,62,502,'Active','');

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
