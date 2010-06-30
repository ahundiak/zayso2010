<?php
class Osso_Person_PersonExport
{
  protected $context;
  protected $db;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->db = $this->context->dbOsso2007;
  }
  protected function getMasterQuery()
  {
    // Master person query with phones ans emails
    $sql = <<<EOT
SELECT
  person.person_id AS id,
  person.aysoid    AS aysoid,
  unit.keyx        AS region,
  person.lname     AS lname,
  person.fname     AS fname,
  person.nname     AS nname,
  person.mname     AS mname,
  person.status    AS status,

  CONCAT(phone_home.area_code,'-',phone_home.num,phone_home.ext) AS phone_home,
  CONCAT(phone_work.area_code,'-',phone_work.num,phone_work.ext) AS phone_work,
  CONCAT(phone_cell.area_code,'-',phone_cell.num,phone_cell.ext) AS phone_cell,

  email_home.address AS email_home,
  email_work.address AS email_work

FROM PERSON
LEFT JOIN unit ON unit.unit_id = person.unit_id
LEFT JOIN email AS email_home ON email_home.person_id = person.person_id AND email_home.email_type_id = 1
LEFT JOIN email AS email_work ON email_work.person_id = person.person_id AND email_work.email_type_id = 2

LEFT JOIN phone AS phone_home ON phone_home.person_id = person.person_id AND phone_home.phone_type_id = 1
LEFT JOIN phone AS phone_work ON phone_work.person_id = person.person_id AND phone_work.phone_type_id = 2
LEFT JOIN phone AS phone_cell ON phone_cell.person_id = person.person_id AND phone_cell.phone_type_id = 3

ORDER BY aysoid,lname,fname;
EOT;
    return $sql;
  }
  public function process($outFileName)
  {
    echo "Export_Person execute\n";

    $db = $this->db;

    // Count just because
    $sql = 'SELECT count(*) AS cnt FROM person;';
    $row = $db->fetchRow($sql);

    $this->countPerson = $row['cnt'];

    // Make a master list
    $sql = $this->getMasterQuery();
    $rows = $db->fetchRows($sql);

    $persons = array();
    $aysoids = array();
    foreach($rows as $row)
    {
      // Later info
      $row['member']  = 0;
      $row['coach']   = 0;
      $row['referee'] = 0;

      // Master lst
      $id = $row['id'];
      if (!isset($persons[$id])) $persons[$id] = $row;
      else
      {
        printf("*** Dup Person id %u\n",$id);
      }

      // Master aysoids
      $aysoid = $row['aysoid'];
      if ($aysoid)
      {
        if (!isset($aysoids[$aysoid])) $aysoids[$aysoid] = $row;
        else
        {
          printf("*** Dup AYSOID %s %s %s %u %u\n",
            $aysoid,$row['fname'],$row['lname'],$id,$aysoids[$aysoid]['id']);
        }
      }
    }
    // Add member count
    $sql = 'SELECT * FROM member;';
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $id = $row['person_id'];
      if ($id)
      {
        if (isset($persons[$id])) $persons[$id]['member']++;
        else
        {
          printf("Member id: %u, Person id: %u\n",$row['member_id'],$id);
        }
      }
    }
    // Add coach count
    $sql = 'SELECT * FROM phy_team_person;';
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $id = $row['person_id'];
      if ($id)
      {
        if (isset($persons[$id])) $persons[$id]['coach']++;
        else
        {
          printf("Coach id: %u, Person id: %u\n",$row['phy_team_person_id'],$id);
        }
      }
    }
    // Add referee count
    $sql = 'SELECT * FROM event_person;';
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $id = $row['person_id'];
      if ($id)
      {
        if (isset($persons[$id])) $persons[$id]['referee']++;
        else
        {
          printf("Referee id: %u, Person id: %u\n",$row['event_person_id'],$id);
        }
      }
    }
    // Check phones
    $sql  = "SELECT distinct person_id FROM phone;";
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $id = $row['person_id'];
      if (!isset($persons[$id]))
      {
        printf("Phone person: %u\n",$id);
        $db->delete('phone','person_id',$id);
      }
    }
    // Check emails
    $sql = "SELECT distinct person_id FROM email;";
    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $id = $row['person_id'];
      if (!isset($persons[$id]))
      {
        printf("Email person: %u\n",$id);
        $db->delete('email','person_id',$id);
      }
    }
    // Dump it
    $fp = fopen($outFileName,'w');
    if (!$fp) die("Could not open $outFileName\n");
    
    $header = NULL;
    foreach($persons as $person)
    {
      if (!$header)
      {
        $header = array_keys($person);
        fputcsv($fp,$header);
      }
      fputcsv($fp,$person);
    }
    fclose($fp);
  }
  protected function processDups($dups)
  {
    $db = $this->context->dbOsso2007;

    foreach($dups as $dup)
    {
      $row1 = $dup['row1'];
      $row2 = $dup['row2'];

      printf("==============================\n");
      $this->find($row1['id']);
      $this->find($row2['id']);

      $keys = array_keys($row1);
      foreach($keys as $key)
      {
        //printf("%20s %20s\n",$row1[$key],$row2[$key]);
      }
    }
    // ID 2429 needs merge into 1474
    // $this->merge(2429,1474);
  }
  protected function merge($id1,$id2)
  {
    $db = $this->context->dbOsso2007;

    $sql = <<<EOT
SELECT
  person.person_id AS id,
  person.fname  AS fname,
  person.lname  AS lname,
  phone.num     AS phone,
  email.address AS email
FROM person
LEFT JOIN email ON email.person_id = person.person_id
LEFT JOIN phone ON phone.person_id = person.person_id
WHERE person.person_id IN ($id1,$id2);

EOT;
    $rows = $db->fetchRows($sql);
    // Cerad_Debug::dump($rows);
    foreach($rows as $row)
    {

    }
    $this->find($id1);
    $this->find($id2);
    
  }
  protected function find($id)
  {
    $db = $this->context->dbOsso2007;

    echo "=== $id ===\n";

    $sql = "SELECT count(*) as cnt FROM vol WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Vol Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM phone WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Phone Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM email WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Email Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM phy_team_person WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Phy_team_Person Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM phy_team_referee WHERE referee_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Phy_team_Referee Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM ref_avail WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Ref Avail Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM event_person WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Event_person Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM member WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Member Count {$row['cnt']}\n";

    $sql = "SELECT count(*) as cnt FROM family_person WHERE person_id = $id;";
    $row = $db->fetchRow($sql);
    echo "Family Person Count {$row['cnt']}\n";




  }
}

?>
