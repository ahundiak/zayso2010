<?php

namespace Zayso\NatGamesBundle\Controller;

class ProjectController extends BaseController
{
    protected $plansData  = null;
    protected $levelsData = null;

    protected $pickLists = null;

    public function plansAction()
    {
        // Check user
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));
        
        $plansData = $user->getProjectPerson()->get('plans');
        if (!$plansData) $plansData = array();

        $this->plansData = $plansData;
        $this->buildPlansPickLists();

        $tplData = $this->getTplData();
        $tplData['gen']  = $this;
        return $this->render('NatGamesBundle:Project:plans.html.twig',$tplData);
    }
    public function levelsAction()
    {
        // Check user
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $levelsData = $user->getProjectPerson()->get('levels');
        if (!$levelsData) $levelsData = array();
        $this->levelsData = $levelsData;

        $levels = array
        (
            array('desc' => 'Regular Pool Play', 'cat' => 'pp'),
            array('desc' => 'Play Offs',         'cat' => 'po'),
            array('desc' => 'Jamboree',          'cat' => 'ja'),
            array('desc' => 'EXTRA',             'cat' => 'ex'),
        );

        $tplData = $this->getTplData();
        $tplData['gen']    = $this;
        $tplData['levels'] = $levels;
        $tplData['ages']   = array('U10','U12','U14','U16','U19');
        return $this->render('NatGamesBundle:Project:levels.html.twig',$tplData);
    }
    public function plansPostAction()
    {
        // Check user
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $request = $this->getRequest();

        $plansData = $request->request->get('plansData');
        if (!$plansData) $plansData = array();

        $user->getProjectPerson()->set('plans',$plansData);
        $this->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('_natgames_project_plans'));
    }
    public function levelsPostAction()
    {
        // Check user
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $request = $this->getRequest();

        $levelsData = $request->request->get('levelsData');
        if (!$levelsData) $levelsData = array();

        $user->getProjectPerson()->set('levels',$levelsData);
        $this->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('_natgames_project_levels'));
    }
    protected function getPlansDataValue($name)
    {
        if (!isset($this->plansData[$name])) return null;
        return htmlspecialchars($this->plansData[$name],ENT_QUOTES);
    }
    public function genSelect($name)
    {
        $value = $this->getPlansDataValue($name);

        $html = sprintf('%s<select name="plansData[%s]">%s',"\n",$name,"\n");

        $options = $this->pickLists[$name];
        foreach($options as $key => $desc)
        {
            if ($value == $key) $selected = 'selected="selected"';
            else                $selected = '';
            $option = sprintf('<option value="%s" %s>%s</option>%s',$key,$selected,$desc,"\n");
            $html .= $option;
        }
        $html .= '</select>' . "\n";
        return $html;
    }
    public function genCheckBox($name)
    {
        $value = $this->getPlansDataValue($name);

        if ($value) $checked = 'checked="checked"';
        else        $checked = '';

        $html = sprintf('<input type="checkbox" name=plansData[%s]" value="1" %s />',$name,$checked);

        return $html;
    }
    public function genLevelCheckBox($cat,$name)
    {
        $html = '<td align="center"><input type="checkbox" value="1" name="';

        $html .= 'levelsData' . '[' . $cat . ']' . '[' . $name . ']" ' ;

        // See if checked
        if (isset($this->levelsData[$cat][$name]))
        {
            $html .= ' checked="checked" ';
        }
        $html .= '/></td>' . "\n";

        // Bit of a hack for extras
        if ($cat == 'ex')
        {
            $age = substr($name,0,3);
            switch($age)
            {
                case 'U10':
                case 'U16':
                case 'U19':
                    $html = '<td>&nbsp;</td>' . "\n";
            }
        }
        return $html;
    }
    public function genRoomMateName($name)
    {
        $value = $this->getPlansDataValue($name);

        $html = sprintf('<input type="text" name=plansData[%s]" size="30" value="%s" />',$name,$value);

        return $html;
    }
    protected function buildPlansPickLists()
    {
        $yesno = array
        (
            'NA'    => 'Select Answer',
            'Yes'   => 'Yes',
            'No'    => 'No',
        );
        $yesnoref = array
        (
            'NA'    => 'Select Answer',
            'Yes'   => 'Yes - Will referee',
            'No'    => 'No - Will not referee',
        );
        $yesnorefx = array
        (
            'NA'    => 'Select Answer',
            'Yes'   => 'Yes - Will referee',
            'Yesx'  => 'Yes - Will referee if my team advances',
            'No'    => 'No - Will not referee',
        );

        $pickLists = array
        (
            'attend' => array
            (
                'NA'    => 'Select Answer',
                'Yes'   => 'Yes - For sure',
                'Yesx'  => 'Yes - If my team is selected',
                'No'    => 'No',
                'Maybe' => 'Maybe - Not sure yet',
            ),
            'will_referee'   => $yesno,
            'do_assessments' => $yesno,
            'coaching'       => $yesno,
            'other_jobs'     => $yesno,

            'have_player' => array
            (
                'NA'    => 'Select Answer',
                'Yes'   => 'Yes',
                'Yesx'  => 'Yes - I am a player',
                'No'    => 'No',
            ),
            'want_assessment' => array
            (
                'NA'            => 'Select Answer',
                'No'            => 'No',
                'Informal'      => 'Informal',
                'Intermediate'  => 'Intermediate',
                'AdvancedCR'    => 'Advanced CR',
                'AdvancedAR'    => 'Advanced AR',
                'AdvancedCRAR'  => 'Advanced CR and AR',
                'NationalCR'    => 'National CR',
                'NationalAR'    => 'National AR',
                'NationalCRAR'  => 'National CR and AR',
            ),
            'attend_open' => array
            (
                'NA'    => 'Select Answer',
                'Yes'   => 'Yes - Will participate',
                'No'    => 'No - Will not be there',
            ),
            'avail_wed'       => $yesnoref,
            'avail_thu'       => $yesnoref,
            'avail_fri'       => $yesnoref,
            'avail_sat_morn'  => $yesnoref,
            'avail_sat_after' => $yesnoref,
            'avail_sun_morn'  => $yesnorefx,
            'avail_sun_after' => $yesnorefx,
        );
        $this->pickLists = $pickLists;
    }
}
