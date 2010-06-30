<?php
class Eayso_VolRepo extends Base_BaseRepo
{
    function queryForAysoid($aysoid)
    {
        $db = $this->context->dbEayso;
        $item = new Eayso_VolItem();
        if (!$aysoid) return $item;
        
        // Grab the main data structure
        $sql = 'SELECT * FROM eayso_vol WHERE aysoid = :aysoid;';
        $search['aysoid'] = $aysoid;
        $row = $db->fetchRow($sql,$search);
        if (!$row) return $item;

        $item->id     = $row['eayso_vol_id'];
        $item->aysoid = $row['aysoid'];
        $item->region = $row['region'];

        $item->lname  = $row['lname'];
        $item->fname  = $row['fname'];
        $item->nname  = $row['nname'];
        $item->mname  = $row['mname'];

        $item->email  = $row['email'];

        $item->phoneHome  = $row['phone_home'];
        $item->phoneWork  = $row['phone_work'];
        $item->phoneCell  = $row['phone_cell'];

        $item->status = $row['status'];
        $item->season = $row['season'];

        $item->dob     = $row['dob'];
        $item->gender  = $row['gender'];
        $item->regDate = $row['reg_date'];

        return $item;
    }
    function queryCertsForAysoid($aysoid)
    {
        $db = $this->context->dbEayso;

        $item = $this->queryForAysoid($aysoid);
        if (!$item->id) return $item;

        $item->certSafeHaven    = NULL;
        $item->certRefereeBadge = NULL;
        $item->certCoachBadge   = NULL;

        $item->certSafeHavenDate    = NULL;
        $item->certRefereeBadgeDate = NULL;
        $item->certCoachBadgeDate   = NULL;

        // Grab the certs
        $sql = 'SELECT * FROM eayso_vol_cert WHERE aysoid = :aysoid;';
        $search['aysoid'] = $aysoid;
        $rows = $db->fetchRows($sql,$search);

        $certRepo = new Eayso_VolCertRepo($this->context);

        foreach($rows as $row)
        {
            switch($row['cert_cat'])
            {
                case Eayso_VolCertRepo::TYPE_SAFE_HAVEN:
                    $item->certSafeHaven     = $certRepo->getDescx($row['cert_type']);
                    $item->certSafeHavenDate = $row['cert_date'];
                    break;

                case Eayso_VolCertRepo::TYPE_COACH_BADGE:
                    $item->certCoachBadge     = $certRepo->getDescx($row['cert_type']);
                    $item->certCoachBadgeDate = $row['cert_date'];
                    break;

                case Eayso_VolCertRepo::TYPE_REFEREE_BADGE:
                    $item->certRefereeBadge     = $certRepo->getDescx($row['cert_type']);
                    $item->certRefereeBadgeDate = $row['cert_date'];
                    break;

            }
        }
        return $item;
    }
}
?>
