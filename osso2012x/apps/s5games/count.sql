select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 62;

select count(*) from event_person left join event on event.id = event_person.event_id where event.project_id = 62 and event_person.person_id IS NOT NULL;
