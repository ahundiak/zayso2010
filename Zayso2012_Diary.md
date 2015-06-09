## 21 Nov 2011 Mon ##

### Project Stuff ###
Made projects.csv from osso2007
Added ProjectManager and ProjectImport
Added ProjectGroup and projectParentId just for grins
Probably need to add a few more fields to ProjectEntity including a blob.

### Authorization ###
Got the authentication and authorization working reasonably well.
Checked it all into production.
Even added mailer to create account.

## 15 Nov 2011 Tue ##

Want to work through security system as well as review building a new bundles from stratch

## 11 Nov 2011 ##

Upgraded to Symfony 2.0.5 using git on buffy then moving zip file around
Added FOSUserBundle
Seemed to work okay.

The zip file was 89mb.  Get rid of .git and it becomes much more reasonable

Also moved FormTypes to services.
Need to see if it makes sense to move records to a form type as that allows me to process the output.

## 10 Nov 2011 ##

### Account Person Add ###
Got the basics working

Learned to use choice validation and validation groups

### Account Person Relations ###
Dropped the rel\_id and replaced with accountRelation.
Values: 'Primary','Family','Peer'

### Initial Post ###
Trying various ways to document the progress of my work.
Neither google docs nor google blogs work in my solaris 10 enviroment.

Wiki might get the job done.
```
svn checkout https://zayso2010.googlecode.com/svn/wiki wiki --username ahundiak@gmail.com
```
Can't use folders.  Kind of sad.

## 09 Nov 2011 ##

### Hints - Entity Update Workflow ###
```
1. Modify entity file
2. console doctrine:schema:update --dump-sql
3. console doctrine:generate:entities ZaysoBundle:AccountPerson
```

### Hints - Useful console commands ###
```
console router:debug
console container:debug
```