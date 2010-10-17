<?php
/* ==========================================================
 * Try for a very simple sql builder
 */
class Cerad_Repo_RepoSQL
{
  public $selects = array();
  public $joins   = array();
  public $wheres  = array();
  public $sorts   = array();

  public function __toString()
  {
    $selects = implode(",\n",$this->selects);
    $joins   = implode(" \n",$this->joins);

    if (!count($this->wheres)) $wheres = null;
    else                       $wheres = "WHERE\n" . implode(" AND\n",$this->wheres);

    if (!count($this->sorts)) $sorts = null;
    else                      $sorts = "ORDER BY\n" . implode(",\n",$this->sorts);

    $sql = <<<EOT
SELECT
$selects
$joins
$wheres
$sorts;
EOT;
    return $sql;
  }
}
?>
