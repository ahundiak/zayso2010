select num,id,datex,timex from event where project_id = 82 and num in (145,146,151,152);

# 145 3844
# 145 3845
# 151 3850
# 152 3851

mysql> select * from event_team where event_id in (3844,3845,3850,3851);
+------+----------+---------+------+-------+------------+
| id   | event_id | team_id | type | datax | project_id |
+------+----------+---------+------+-------+------------+
| 7688 |     3844 |    2786 | Home | NULL  |         82 |
| 7689 |     3844 |    2787 | Away | NULL  |         82 |
| 7690 |     3845 |    2788 | Home | NULL  |         82 |
| 7691 |     3845 |    2789 | Away | NULL  |         82 |
| 7700 |     3850 |    2786 | Home | NULL  |         82 |
| 7701 |     3850 |    2788 | Away | NULL  |         82 |
| 7702 |     3851 |    2787 | Home | NULL  |         82 |
| 7703 |     3851 |    2789 | Away | NULL  |         82 |
+------+----------+---------+------+-------+------------+

mysql> select id,key1,desc1 from team where project_id = 82 and age = 'U12' and gender = 'B' and desc1 like 'A%';
+------+------------+-------------------------------+
| id   | key1       | desc1                         |
+------+------------+-------------------------------+
| 2786 | U12B PP A1 | A1 R0498-U12B-01-Paulett      |
| 2787 | U12B PP A2 | A2 R0498-U12B-03-Swartz       |
| 2788 | U12B PP A3 | A3 R0498-U12B-05-Russell      |
| 2789 | U12B PP A4 | A4 R0498-U12B-07-Thammavongsa |
+------+------------+-------------------------------+
 

145
A1 R0498-U12B-01-Paulett
A2 R0498-U12B-03-Swartz A3 R0498-U12B-05-Russell

146
A3 R0498-U12B-05-Russell A2 R0498-U12B-03-Swartz
A4 R0498-U12B-07-Thammavongsa

151
A1 R0498-U12B-01-Paulett
A3 R0498-U12B-05-Russell A2 R0498-U12B-03-Swartz

152
A2 R0498-U12B-03-Swartz A3 R0498-U12B-05-Russell
A4 R0498-U12B-07-Thammavongsa

update event_team set team_id = 2788 where id = 7689; # 145 Away Russell
update event_team set team_id = 2787 where id = 7690; # 146 Home Swartz
update event_team set team_id = 2787 where id = 7701; # 151 Away Swartz
update event_team set team_id = 2788 where id = 7702; # 152 Home Russell
