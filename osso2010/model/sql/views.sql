USE osso;

DROP VIEW IF EXISTS person_view1;
CREATE VIEW         person_view1 AS
SELECT
  person_reg_type.keyx        AS reg_type,
  person_reg.person_reg_num   AS reg_num,
  person_reg.person_reg_year  AS reg_year,
  person.lname AS lname,
  person.fname AS fname,
  person.nname AS nname
FROM person
LEFT JOIN person_reg      ON person_reg.person_id = person.id
LEFT JOIN person_reg_type ON person_reg_type.id = person_reg.person_reg_type_id
ORDER BY lname,fname,reg_type,reg_num;

-- Account
DROP VIEW IF EXISTS account_view1;
CREATE VIEW         account_view1 AS
SELECT
  account.id           AS account_id,
  account.user_name    AS user_name,
  account_person.id    AS account_person_id,
  account_person.person_id AS person_idx,
  person.id            AS person_id,
  account.lname        AS lname,
  account_person.fname AS fname,
  person.fname         AS person_fname,
  person.lname         AS person_lname
FROM account
LEFT JOIN account_person ON account_person.account_id = account.id
LEFT JOIN person         ON person.id = account_person.person_id
;

-- User Data
DROP VIEW IF EXISTS user_data_view;
CREATE VIEW         user_data_view AS
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
  person.dob               AS person_dob,

  person_reg.person_reg_type_id AS cert_source,

  person_reg_cert.id        AS cert_id,
  person_reg_cert.cert_cat  AS cert_cat,
  person_reg_cert.cert_type AS cert_type,
  person_reg_cert.cert_date AS cert_date,
  person_reg_cert.cert_desc AS cert_desc

FROM account_person
LEFT JOIN account         ON account.id = account_person.account_id
LEFT JOIN org             ON org.id = account_person.org_id
LEFT JOIN person          ON person.id = account_person.person_id
LEFT JOIN person_reg      ON person_reg.person_id = person.id
LEFT JOIN person_reg_cert ON person_reg_cert.person_reg_id = person_reg.id
;

-- ======================================================
-- Organization group view
DROP VIEW IF EXISTS org_group_org_view;
CREATE VIEW         org_group_org_view AS
SELECT
  org_group.id    AS org_group_id,
  org_group.keyx  AS org_group_key,
  org_group.desc1 AS org_group_desc,
  org_group.sortx AS org_group_sort,
  org.id          AS org_id,
  org.keyx        AS org_key,
  org.desc1       AS org_desc,
  org_type.keyx   AS org_type

FROM org_group
LEFT JOIN org_group_org ON org_group_org.org_group_id = org_group.id
LEFT JOIN org           ON org.id = org_group_org.org_id
LEFT JOIN org_type      ON org_type.id = org.org_type_id
;
