
DROP VIEW IF EXISTS user_view;

CREATE VIEW         user_view AS
SELECT
  account.account_id       AS account_id,
  account.account_user     AS account_uname,
  account.account_name     AS account_lname,

  member.member_id         AS member_id,
  member.member_name       AS member_fname,
  member.level             AS member_level,

  member.person_id         AS person_id

FROM      osso2007.account AS account
LEFT JOIN osso2007.member  AS member ON member.account_id = account.account_id AND member.level = 1
;
