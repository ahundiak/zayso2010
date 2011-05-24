use osso2012v;

DROP VIEW IF EXISTS user_view;

CREATE VIEW         user_view AS
SELECT
  account.id        AS account_id,
  account.uname     AS account_uname,

  account_person.id        AS user_id,
  account_person.person_id AS person_id,

  person.fname AS person_fname,
  person.lname AS person_lname,
  person.nname AS person_nname,
  person.guid  AS person_guid,

  vol.reg_year AS vol_reg_year,

  cert_type_coach.desc1      AS cert_type_coach_desc1,
  cert_type_referee.desc1    AS cert_type_referee_desc1,
  cert_type_safe_haven.desc1 AS cert_type_safe_haven_desc1

FROM      osso2012.accounts        AS account
LEFT JOIN osso2012.account_person  AS account_person ON account.id = account_person.account_id
LEFT JOIN osso2012.persons         AS person         ON person.id  = account_person.person_id
LEFT JOIN eayso.reg_main           AS vol            ON vol.reg_num = person.guid

LEFT JOIN eayso.reg_cert           AS cert_referee    ON vol.reg_num = cert_referee.reg_num    AND cert_referee.catx    = 200
LEFT JOIN eayso.reg_cert           AS cert_safe_haven ON vol.reg_num = cert_safe_haven.reg_num AND cert_safe_haven.catx = 100
LEFT JOIN eayso.reg_cert           AS cert_coach      ON vol.reg_num = cert_coach.reg_num      AND cert_coach.catx      = 300

LEFT JOIN eayso.reg_cert_type      AS cert_type_referee    ON cert_type_referee.id    = cert_referee.typex
LEFT JOIN eayso.reg_cert_type      AS cert_type_safe_haven ON cert_type_safe_haven.id = cert_safe_haven.typex
LEFT JOIN eayso.reg_cert_type      AS cert_type_coach      ON cert_type_coach.id      = cert_coach.typex

;
