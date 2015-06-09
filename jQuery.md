jQuery brings:

1. Tabbed interface
2. ajax form submission
3. calendar control

Not sure how well it degrades.

1. Load main page with dynamic tabs
2. User sees welcome page and possibly other tabs (Field Status, Training, Schedules) as well
2.a. Create account
2.b. Navigate to other tabs
2.c. Log on

3. Log on
3.a. Remove welcome tab, replace with home tab
3.b. Add additional tabs

Also need password recovery assistance for failed sign ins

How to load individual tabs?
tab/name
direct/login
load/name
html/name

Is there really a difference between action and tab?  Don't need a master page for tabs of course

Welcome Page
> Allow logging on - implies executing assorted code including previous use log on info from session

direct/account/authenticate -

direct/referee/signup
> needs to verify authenticated and a referee

action/account/login
> uses direct to authenticate
> sets up user and what not

direct - returns json, goes directly to database
tab    - retruns html
action - for the classic version, redirects and returns a full html page
> - for ajax version, return json results