
DROP VIEW IF EXISTS reg_main_view_all;
CREATE VIEW         reg_main_view_all AS
SELECT
  reg_main.reg_type AS reg_main_reg_type,
  reg_main.reg_num  AS reg_main_reg_num,
  reg_main.fname    AS reg_main_fname,
  reg_main.lname    AS reg_main_lname,
  reg_main.nname    AS reg_main_nname,
  reg_org.org_id    AS reg_org_org_id,
  org.desc1         AS org_desc1,
  reg_cert.catx     AS reg_cert_cat,
  reg_cert.typex    AS reg_cert_type,
  reg_cert.datex    AS reg_cert_date,
  reg_prop.typex    AS reg_prop_type,
  reg_prop.valuex   AS reg_prop_value

FROM reg_main

LEFT JOIN reg_org ON
  reg_org.reg_type = reg_main.reg_type AND
  reg_org.reg_num  = reg_main.reg_num

LEFT JOIN reg_prop ON
  reg_prop.reg_type = reg_main.reg_type AND
  reg_prop.reg_num  = reg_main.reg_num

LEFT JOIN reg_cert ON
  reg_cert.reg_type = reg_main.reg_type AND
  reg_cert.reg_num  = reg_main.reg_num

LEFT JOIN osso.org AS org ON org.id = reg_org.org_id
;

-- =========================================
-- Same but with simpiler outputs
DROP VIEW IF EXISTS reg_main_view_all2;
CREATE VIEW         reg_main_view_all2 AS
SELECT
  reg_main.reg_type AS reg_type,
  reg_main.reg_num  AS reg_num,
  reg_main.reg_year AS reg_year,
  reg_main.fname    AS fname,
  reg_main.lname    AS lname,
  reg_main.nname    AS nname,
  reg_main.dob      AS dob,
  reg_main.sex      AS sex,
  reg_org.org_id    AS org_id,
  org.keyx          AS org_key,
  org.desc1         AS org_desc1,
  reg_cert.catx     AS cert_cat,
  reg_cert.typex    AS cert_type,
  reg_cert.datex    AS cert_date,
  reg_prop.typex    AS prop_type,
  reg_prop.valuex   AS prop_value

FROM reg_main

LEFT JOIN reg_org ON
  reg_org.reg_type = reg_main.reg_type AND
  reg_org.reg_num  = reg_main.reg_num

LEFT JOIN reg_prop ON
  reg_prop.reg_type = reg_main.reg_type AND
  reg_prop.reg_num  = reg_main.reg_num

LEFT JOIN reg_cert ON
  reg_cert.reg_type = reg_main.reg_type AND
  reg_cert.reg_num  = reg_main.reg_num

LEFT JOIN osso.org AS org ON org.id = reg_org.org_id
;
