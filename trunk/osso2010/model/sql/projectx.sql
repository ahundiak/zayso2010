ALTER TABLE sch_team
ADD COLUMN project_id int(10) unsigned default 0
AFTER phy_team_id;

ALTER TABLE sch_team
ADD KEY ip (project_id,desc_short);

ALTER TABLE event
ADD COLUMN project_id int(10) unsigned default 0
AFTER event_num;

UPDATE event_team SET unit_id = 1 WHERE unit_id = 0;

-- Finds events with no home teams
SELECT event.event_id
FROM   event LEFT JOIN event_team ON event_team.event_id = event.event_id AND event_team_type_id = 1
WHERE  event_team_id IS NULL;

-- find evenst with no teams
SELECT event.event_id
FROM   event LEFT JOIN event_team ON event_team.event_id = event.event_id
WHERE  event_team_id IS NULL;

