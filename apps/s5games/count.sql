select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 62;

select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 62 and event_person.person_id IS NOT NULL;

select count(distinct event_person.person_id) from event_person left join event on event.id = event_person.event_id where event.project_id = 62;

select count(*) from project_person where project_id = 62;

select event.project_id, event.id, event.pool, event_person.id,event_person.person_id, event_person.state
from event_person left join event on event.id = event_person.event_id
where project_id = 62 and event_person.person_id is not null and event_person.state is null;

select event.project_id, event.id as game_id, event.pool, 
event_person.id as game_person_id, event_person.person_id, event_person.state, project_person.id as project_person_id
from event_person 
left join event on event.id = event_person.event_id
left join project_person on project_person.person_id = event_person.person_id and project_person.project_id = 62
where event.project_id = 62 and event_person.person_id is not null and project_person.id is null;

update event_person set state = 'AssignmentRequested' where
id in (4569,4635,4609,4710,4709,4733,4799,4802,4777,4821,4794,4871,4936,4954,4959,4960,4963,4961,4990,4994,5019,5069);

; Alan V
insert into project_person values(NULL,62,499,'Active','');
insert into project_person values(NULL,62,500,'Active','');
insert into project_person values(NULL,62,501,'Active','');
insert into project_person values(NULL,62,502,'Active','');

; Jim Green
insert into project_person values(NULL,62,414,'Active','');

update event_person set state = 'AssignmentRequested' where
id in (4633,4663,4842,4790,4822,4879,4890,4957);

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
