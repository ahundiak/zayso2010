# Background #

The basic goal is to have a person table with one entry for each person in the system.
Two basic categories of persons, players and the rest (coaches, referees, managers, volunteers) etc.
The same person can be both a player as well as a volunteer.  Might have some trouble linking
volunteer records with their player record.

AYSO volunteers and players are tracked in a database known as eayso.  Currently have about 23,000 volunteers
just for Section 5.  Of course many of these volunteers are no longer active.
The osso2007 database had about 2000 person records though again many of those are no longer active.

Need three tables in each registered database
person\_reg
person\_reg\_cert
person\_reg\_org

If I have a global person\_reg\_org database then I cannot update without access to the master
The org information is in the exported eayso information so I can't add later.
Means that I will also have to maintain access to the master org database.

On the other hand, means that I don't need a person\_reg\_type\_id in it.

It's tempting to treat eayso as read only and pull records in to osso when they get used.

As it stands right now, adding a person to osso requires updating person\_id in eayso.person\_reg.
That knocks out the standalone capability.

When an eayso record is updated (as the ts will reveal) then a script could run to copy the updates.

But as long as all the updates are done on the server then ok.

The trick is going to be to make the primary key for person\_reg be type.reg\_num and never use the id.

Should probably break down person\_reg to person\_reg\_data using the same key

Make them num and type

The person\_id is only used in the main osso database

person
> person\_id
> fname
> lname

> person\_reg
> > type,num
> > person\_id


> person\_reg\_data
> > type,num (fname,lname etc)

> person\_reg\_org

Instead of an individual type lets encode the reg\_num
Axxx AYSO
Pxxx AYSO Player
Oxxx OSSO
U
N




