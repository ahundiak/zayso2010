-- =========================================
-- Grabs the info needed by the user object
-- Really don't need phones/emails
-- Get name and region from account information
DROP VIEW IF EXISTS reg_view_user;
CREATE VIEW         reg_view_user AS
SELECT
  reg_main.reg_type AS reg_typex, -- need for joins
  reg_main.reg_num  AS reg_numx,  -- want to support .* column selections

  reg_main.reg_year AS reg_year,
  reg_main.dob      AS dob,
  reg_main.sex      AS sex,

  reg_cert.catx     AS cert_cat,
  reg_cert.typex    AS cert_type,
  reg_cert.datex    AS cert_date

FROM reg_main

LEFT JOIN reg_cert ON
  reg_cert.reg_type = reg_main.reg_type AND
  reg_cert.reg_num  = reg_main.reg_num
;
-- =========================================
-- Grabs main/cert/org info

DROP VIEW IF EXISTS reg_view_info;
CREATE VIEW         reg_view_info AS
SELECT
  reg_main.reg_type AS reg_type,
  reg_main.reg_num  AS reg_num,
  reg_main.reg_year AS reg_year,

  reg_main.fname    AS fname,
  reg_main.lname    AS lname,
  reg_main.nname    AS nname,
  reg_main.mname    AS mname,
  reg_main.sname    AS sname,

  reg_main.dob      AS dob,
  reg_main.sex      AS sex,
  reg_main.status   AS status,

  reg_cert.catx     AS cert_cat,
  reg_cert.typex    AS cert_type,
  reg_cert.datex    AS cert_date,

  reg_org.org_id    AS org_id

FROM reg_main

LEFT JOIN reg_cert ON
  reg_cert.reg_type = reg_main.reg_type AND
  reg_cert.reg_num  = reg_main.reg_num

LEFT JOIN reg_org ON
  reg_org.reg_type = reg_main.reg_type AND
  reg_org.reg_num  = reg_main.reg_num
;
