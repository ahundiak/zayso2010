ALTER TABLE sch_team
ADD COLUMN project_id int(10) unsigned default 0
AFTER phy_team_id;

ALTER TABLE event
ADD COLUMN project_id int(10) unsigned default 0
AFTER event_num;

ALTER TABLE phy_team
ADD COLUMN type_id int(10) unsigned default 0
AFTER phy_team_id;
