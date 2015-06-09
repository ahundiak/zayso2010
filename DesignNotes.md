## To Do ##
  * Learn to use wiki effectively

  * Robust eayso import
    * Volunteers
    * Certifications
    * Players
    * Teams/Rosters

  * Imported eayso viewer
    * Volunteers and certifications
    * Teams with link into zayso

  * Robust schedule import

  * Additional user capabilities
    * My Account
    * My Teams
    * My Schedule
    * Game Schedules
    * Referee Schedules
      * Allow viewing coach contact info for games

  * Points System

  * Additional admin
    * Points Report
    * Game and referee coverage reports

### Big Picture ###

One of the biggest issues is the trade off between keeping this ayso specific and wanting it to encompass ussf and nfhs and even other sports besides soccer.  Constant battle to stay focused.

But with the section five games 2011 as a possible user as well as the national games 2012 it's important to get the under pinning built to handle ayso in a very robust fashion.

The user interface is also a constant distraction.  Simple html is quick and gets the job done.  But javascript based libraries such as extjs are very tempting.  Need to primarily focus on html but try to keep the back end as generic as possible.


### WIKI Training ###
So what happens when I try to make an indented list without the special characters?

Chap 1
> Page 1
> > Line 1
> > Line 2

This does not work so good: code language="php"
Three squigglies work fine.

```
namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Zayso\ZaysoBundle\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     *  ORM\GeneratedValue
     */
    protected $id;
```