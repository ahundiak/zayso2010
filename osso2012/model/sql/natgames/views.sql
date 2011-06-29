use natgamesv;

DROP VIEW IF EXISTS user_view;

CREATE VIEW         user_view AS
SELECT
  account.id        AS account_id,
  account.uname     AS account_uname,
  account.status    AS account_status,
  account.lname     AS account_lname,
  account.email     AS account_email,
  account.phonec    AS account_phonec,
  account.aysoid    AS account_aysoid,
  account.verified  AS account_verified,

  vol.fname AS person_fname,
  vol.lname AS person_lname,
  vol.nname AS person_nname,

  vol.dob    AS person_dob,
  vol.sex    AS person_gender,

  vol.reg_year AS vol_reg_year,

  cert_type_coach.desc1      AS cert_type_coach_desc1,
  cert_type_referee.desc1    AS cert_type_referee_desc1,
  cert_type_safe_haven.desc1 AS cert_type_safe_haven_desc1,

  reg_email.valuex  AS person_email,
  reg_phonec.valuex AS person_phonec,

  org.keyx  AS person_org_key,
  org.desc1 AS person_org_desc

FROM      s5games.accounts         AS account
LEFT JOIN eayso.reg_main           AS vol            ON vol.reg_num = account.aysoid

LEFT JOIN eayso.reg_cert           AS cert_referee    ON vol.reg_num = cert_referee.reg_num    AND cert_referee.catx    = 200
LEFT JOIN eayso.reg_cert           AS cert_safe_haven ON vol.reg_num = cert_safe_haven.reg_num AND cert_safe_haven.catx = 100
LEFT JOIN eayso.reg_cert           AS cert_coach      ON vol.reg_num = cert_coach.reg_num      AND cert_coach.catx      = 300

LEFT JOIN eayso.reg_cert_type      AS cert_type_referee    ON cert_type_referee.id    = cert_referee.typex
LEFT JOIN eayso.reg_cert_type      AS cert_type_safe_haven ON cert_type_safe_haven.id = cert_safe_haven.typex
LEFT JOIN eayso.reg_cert_type      AS cert_type_coach      ON cert_type_coach.id      = cert_coach.typex

LEFT JOIN eayso.reg_prop           AS reg_email  ON vol.reg_num = reg_email.reg_num  AND reg_email.typex  = 21
LEFT JOIN eayso.reg_prop           AS reg_phonec ON vol.reg_num = reg_phonec.reg_num AND reg_phonec.typex = 13

LEFT JOIN eayso.reg_org            AS reg_org ON reg_org.reg_num = vol.reg_num
LEFT JOIN osso.org                 AS org     ON org.id          = reg_org.org_id

;

