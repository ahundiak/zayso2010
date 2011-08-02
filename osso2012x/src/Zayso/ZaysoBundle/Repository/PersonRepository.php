<?php
namespace Zayso\ZaysoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectPerson;
use Zayso\ZaysoBundle\Entity\Person;
use Zayso\ZaysoBundle\Entity\PersonRegistered;
use Zayso\ZaysoBundle\Entity\AccountPerson;

class PersonCreateData
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    function __get($name)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return '';
    }
}

/* =========================================================================
 * The person repository
 */
class PersonRepository extends EntityRepository
{
    public function create($data)
    {
        // Some basic error checking
        $em = $this->_em;
        $errors = array();
        if (is_array($data)) $data = new PersonCreateData($data);

        if (!$data->aysoid) $errors[] = 'AYSOID is required';
        if (!$data->fname)  $errors[] = 'AYSO First Name is required';
        if (!$data->lname)  $errors[] = 'AYSO Last Name is required';
        if (!$data->email)  $errors[] = 'Email is required';

        if (count($errors)) return $errors;

        // See if already exists
        $person = $this->findForAysoid($data->aysoid);
        if ($person)
        {
            // Maybe check project person?
            return $person;
        }

        $person = new Person();

        $person->setFirstName($data->fname);
        $person->setLastName ($data->lname);
        $person->setNickName ($data->nname);
        $person->setEmail    ($data->email);
        $person->setCellPhone($data->cphone);

        $orgKey = (int)$data->region;
        if ($orgKey)
        {
            $orgKey = sprintf('AYSOR-%04u',$orgKey);
            $person->setOrgKey($orgKey);
        }
        $person->setStatus('Active');
        $person->setVerified('No');

        $em->persist($person);

        // Registered Person
        $personReg = new PersonRegistered();
        $personReg->setRegType('AYSOV');
        $personReg->setRegKey ('AYSOV-' . $data->aysoid);
        $personReg->setPerson($person);
        $personReg->setVerified('No');

        // Project Person
        $projectId = (int)$data->projectId;
        if ($projectId)
        {
            $projectPerson = new ProjectPerson();
            $projectPerson->setPerson($person);
            $projectPerson->setProject($em->getPartialReference('ZaysoBundle:Project',$projectId));
            $projectPerson->setStatus('Active');
            $em->flush($projectPerson);
        }
        // And save
        try
        {
            $em->flush();
        }
        catch (\Exception $e)
        {
            // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
            $msg = $e->getMessage();
            if (strstr($msg,'Duplicate entry'))
            {
                $errors[] = 'Person ceation failed, already have AYSOID';
            }
            if (!count($errors)) $errors[] = 'Person creation failed, ' . $msg;
            return $errors;
        }
        return $person;
    }
  public function search($search)
  {
    die('account-search');
    $em = $this->_em;
    $qb = $em->createQueryBuilder();
    $qb->addSelect('user');
    $qb->from('\S5Games\User\UserItem','user');
    $qb->addOrderBy('user.account_lname');

    $query = $qb->getQuery();

    $items = $query->getResult();
    return $items;
  }
    public function findForAysoid($aysoid)
    {
        if (strlen($aysoid) == 8) $aysoid = 'AYSOV-' . $aysoid;

        $em = $this->_em;

        $search = array('regKey' => $aysoid);

        $personRegistered = $em->getRepository('ZaysoBundle:PersonRegistered')->findOneBy($search);

        if (!$personRegistered) return null;

        $person = $personRegistered->person;
        if (!$person) return null;

        $person->addRegisteredPerson($personRegistered);
    
        return $person;
    }
}
?>
