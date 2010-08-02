-- User Data from account_person_id
DROP VIEW IF EXISTS user_data_view_eayso;
CREATE VIEW         user_data_view_eayso AS
SELECT
  account_person.id        AS id,
  account_person.fname     AS fname,
  account_person.lname     AS lname,

  account.id               AS account_id,
  account.user_name        AS account_user_name,

  account_person.org_id    AS org_id,
  org.keyx                 AS org_key,
  org.desc1                AS org_desc,

  account_person.person_id AS person_idx,
  person.id                AS person_id,
  person.status            AS person_status,

  person_reg.reg_type      AS reg_type,
  person_reg.reg_num       AS reg_num,

  reg_view_user.*

FROM account_person
LEFT JOIN account             ON account.id = account_person.account_id
LEFT JOIN org                 ON org.id = account_person.org_id
LEFT JOIN person              ON person.id = account_person.person_id
LEFT JOIN person_reg          ON person_reg.person_id = person.id AND person_reg.reg_type = 102
LEFT JOIN eayso.reg_view_user ON reg_view_user.reg_typex = person_reg.reg_type AND reg_view_user.reg_numx = person_reg.reg_num
;
-- ==================================================================
-- Unions are too slow
--
-- User Data from account_person_id
DROP VIEW IF EXISTS user_data_view_osso;
CREATE VIEW         user_data_view_osso AS
SELECT
  account_person.id        AS id,
  account_person.fname     AS fname,
  account_person.lname     AS lname,

  account.id               AS account_id,
  account.user_name        AS account_user_name,

  account_person.org_id    AS org_id,
  org.keyx                 AS org_key,
  org.desc1                AS org_desc,

  account_person.person_id AS person_idx,
  person.id                AS person_id,
  person.status            AS person_status,

  person_reg.reg_type      AS reg_type,
  person_reg.reg_num       AS reg_num,

  reg_view_user.*

FROM account_person
LEFT JOIN account             ON account.id = account_person.account_id
LEFT JOIN org                 ON org.id = account_person.org_id
LEFT JOIN person              ON person.id = account_person.person_id
LEFT JOIN person_reg          ON person_reg.person_id = person.id AND person_reg.reg_type = 101
LEFT JOIN osso.reg_view_user  ON reg_view_user.reg_typex = person_reg.reg_type AND reg_view_user.reg_numx = person_reg.reg_num
;
