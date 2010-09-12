-- ===============================================
-- Clean up events
DROP TABLE IF EXISTS delete_ids;
CREATE TABLE         delete_ids
(
  id int(10) unsigned default 0,
  PRIMARY KEY  (id)
);
-- INSERT INTO delete_ids (id)
--
--  SELECT event.event_id AS id
--  FROM   event
--  LEFT JOIN event_team ON event_team.event_id = event.event_id AND event_team.event_team_type_id = 1
--  WHERE  event_team_id IS NULL
--;
INSERT INTO delete_ids (id)

  SELECT event.event_id AS id
  FROM   event
  WHERE  project_id < 1
;
INSERT INTO delete_ids (id) VALUES(4259); -- sch_team project mismatch

SELECT count(*) FROM delete_ids;
-- SELECT * FROM delete_ids;

DELETE FROM event        USING event        INNER JOIN delete_ids ON delete_ids.id = event.event_id;
DELETE FROM event_team   USING event_team   INNER JOIN delete_ids ON delete_ids.id = event_team.event_id;
DELETE FROM event_person USING event_person INNER JOIN delete_ids ON delete_ids.id = event_person.event_id;

-- For 2002 the region tournment teams actually point to 2003 teams
-- Just delete the events and be done with it

-- UPDATE sch_team,event_team,event
-- SET    sch_team.project_id  = event.project_id
-- WHERE  sch_team.sch_team_id = event_team.team_id AND event_team.event_id = event.event_id;

DROP TABLE IF EXISTS delete_ids;

SELECT
  event.event_id       AS event_id,
  sch_team.sch_team_id AS sch_team_id
FROM sch_team,event_team,event
WHERE
  sch_team.sch_team_id = event_team.team_id AND
  event_team.event_id  = event.event_id     AND
  sch_team.project_id <> event.project_id;

-- Verified that all physical teams have at least one schedule team
-- Query takes a long time to run
-- SELECT
--   phy_team.phy_team_id AS phy_team_id
-- FROM phy_team
-- LEFT JOIN sch_team ON sch_team.phy_team_id = phy_team.phy_team_id
-- WHERE sch_team.sch_team_id IS NULL;
