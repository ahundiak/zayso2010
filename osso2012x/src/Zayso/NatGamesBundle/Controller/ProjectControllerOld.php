<?php

namespace Zayso\NatGamesBundle\Controller;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectPersonPlansFormType extends AbstractType
{
    protected $name  = 'plans';
    protected $group = 'create';

    public function getName() { return $this->name; }
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array();

        if ($this->group) $defaultOptions['validation_groups'] = array($this->group);

        return $defaultOptions;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {   
        $builder->add('attend', 'choice', array(
            'label'         => 'I plan on attending the games',
            'required'      => true,
            'empty_value'   => 'Select Answer',
            'choices'       => array(
                'Yes'   => 'Yes - For sure',
                'Yesx'  => 'Yes - If my team is selected',
                'No'    => 'No',
                'Maybe' => 'Maybe - Not sure yet',
            ),
        ));
        $builder->add('will_referee', 'choice', array(
            'label'         => 'I will referee during the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('do_assessments', 'choice', array(
            'label'         => 'I will do assessments/observations during the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('want_assessment', 'choice', array(
            'label'         => 'Request observation/assessment',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->wantAssessChoices,
        ));
        $builder->add('coaching', 'choice', array(
            'label'         => 'I will coach or manage a team',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('other_jobs', 'choice', array(
            'label'         => 'I will volunteer during the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('have_player', 'choice', array(
            'label'         => 'I have a player in the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => array(
                'Yes'   => 'Yes',
                'Yesx'  => 'Yes - I am a player',
                'No'    => 'No',
            ),
       ));
       $builder->add('ground_transport', 'choice', array(
            'label'         => 'I need ground transportation (shuttle service)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('attend_open', 'choice', array(
            'label'         => 'I will attend the Tuesday Night Opening Ceremony',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
         $builder->add('t_shirt_size', 'choice', array(
            'label'         => 'T-Shirt Size',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->tshirtSizePickList,
        ));
        $builder->add('avail_wed', 'choice', array(
            'label'         => 'Available Wednesday (Jamborees)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('avail_thu', 'choice', array(
            'label'         => 'Available Thursday (Group Play)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('avail_fri', 'choice', array(
            'label'         => 'Available Friday (Group Play)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('avail_sat_morn', 'choice', array(
            'label'         => 'Available Saturday Morning (Group Play)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('avail_sat_after', 'choice', array(
            'label'         => 'Available Saturday Afternoon (Quarter Finals)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('avail_sun_morn', 'choice', array(
            'label'         => 'Available Sunday Morning (Semi-Finals)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => array(
                'Yes'   => 'Yes',
                'Yesx'  => 'Yes - If my team advances',
                'No'    => 'No',
            ),
        ));
        $builder->add('avail_sun_after', 'choice', array(
            'label'         => 'Available Sunday Afternoon (Finals)',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => array(
                'Yes'   => 'Yes',
                'Yesx'  => 'Yes - If my team advances',
                'No'    => 'No',
            ),
       ));
       $builder->add('hotel', 'text', array(
            'label'         => 'Hotel Staying At',
            'required'      => false,
       ));
        //$builder->add('notes', 'textarea', array('label' => 'Notes', 'required' => false, 
        //    'attr' => array('rows' => 4, 'cols' => 80, 'wrap' => 'hard', 'class' =>'textarea')));
        //
        //$builder->get('notes')->appendClientTransformer(new StripTagsTransformer());
    }
    protected $yesNoPickList = array
    (
        'Yes'      => 'Yes',
        'No'       => 'No',
        'Not Sure' => 'Not Sure',
    );
    protected $wantAssessChoices = array
    (
        'No'            => 'No',
        'Informal'      => 'Informal',
        'Intermediate'  => 'Intermediate',
        'AdvancedCR'    => 'Advanced CR',
        'AdvancedAR'    => 'Advanced AR',
        'AdvancedCRAR'  => 'Advanced CR and AR',
        'NationalCR'    => 'National CR',
        'NationalAR'    => 'National AR',
        'NationalCRAR'  => 'National CR and AR',
    );
    protected $tshirtSizePickList = array
    (
        'YM'  => 'Youth Medium',
        'YL'  => 'Youth Large',
        'AS'  => 'Adult Small',
        'AM'  => 'Adult Medium',
        'AL'  => 'Adult Large',
        'AXL' => 'Adult Large X',
        'A2X' => 'Adult Large XX',
        'A3X' => 'Adult Large XXX',
        'A4X' => 'Adult Large XXXX',
    );
 
}

class ProjectController extends BaseController
{
    public function plansAction(Request $request, $id = 0)
    {   
        $plans = array(        
            'attend'           => null,
            'will_referee'     => null,
            'do_assessments'   => null,
            'want_assessment'  => null,
            'coaching'         => null,
            'have_player'      => null,
            'other_jobs'       => null,
            't_shirt_size'     => null,
            'ground_transport' => null,
            'hotel'            => null,
            'attend_open'      => null,
            
            'avail_wed'        => null,
            'avail_thu'        => null,
            'avail_fri'        => null,
            'avail_sat_morn'   => null,
            'avail_sat_after'  => null,
            'avail_sun_morn'   => null,
            'avail_sun_after'  => null,
            
            'room_sun0'        => null,
            'room_mon0'        => null,
            'room_tue1'        => null,
            'room_wed1'        => null,
            'room_thu1'        => null,
            'room_fri1'        => null,
            'room_sat1'        => null,
            'room_sun1'        => null,
            'room_mate1'       => null,
            'room_mate2'       => null,
            'room_mate3'       => null,
        );
        // Load the project person, create one of needed
        if ($id) $personId = $id;
        else     $personId = $this->getUser()->getPersonId();
        
        $manager = $this->get('zayso_core.account.home.manager');
        
        $projectPerson = $manager->addProjectPerson($this->getProjectId(),$personId);
        $plansx = $projectPerson->get('plans');
        
        if (is_array($plansx)) $plans = array_merge($plans,$plansx);
        
        $form = $this->createForm(new ProjectPersonPlansFormType(), $plans);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // Update plans
                $plans = $form->getData();
                $projectPerson->set('plans',$plans);
                $manager->flush();
                
                return $this->redirect($this->generateUrl('zayso_natgames_home'));
            }
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['personId'] = $personId;
        $tplData['projectPerson'] = $projectPerson;

        return $this->render('ZaysoNatGamesBundle:Project:plans.html.twig',$tplData);
    }
    protected $plansData  = null;
    protected $levelsData = null;

    protected $pickLists = null;

    public function plansxAction()
    {   
        $projectPerson = $this->getProjectPerson();
        if (!$projectPerson) return $this->redirect($this->generateUrl('zayso_natgames_home'));
        
        $plansData = $projectPerson->get('plans');
        if (!$plansData) $plansData = array();

        $this->plansData = $plansData;
        $this->buildPlansPickLists();

        $tplData = $this->getTplData();
        $tplData['gen']  = $this;
        return $this->render('ZaysoNatGamesBundle:Project:plans.html.twig',$tplData);
    }
    public function levelsAction(Request $request, $id = 0)
    {
        if ($request->getMethod() == 'POST') return $this->levelsPostAction($request,$id);

        // Load project person creating if need be
        if ($id) $personId = $id;
        else     $personId = $this->getUser()->getPersonId();
        
        $manager = $this->get('zayso_core.account.home.manager');
        $projectPerson = $manager->addProjectPerson($this->getProjectId(),$personId);
        
        if ($request->getMethod() == 'POST') 
        {
            $levelsData = $request->request->get('levelsData');
            $projectPerson->set('levels',$levelsData);
            $manager->flush();

            return $this->redirect($this->generateUrl('zayso_natgames_project_home'));
        }
        $levelsData = $projectPerson->get('levels');
        if (!$levelsData) $levelsData = array();
        
        $this->levelsData = $levelsData;

        $levels = array
        (
            array('desc' => 'Regular Pool Play', 'cat' => 'pp'),
            array('desc' => 'Play Offs',         'cat' => 'po'),
            array('desc' => 'Jamboree',          'cat' => 'ja'),
            array('desc' => 'EXTRA',             'cat' => 'ex'),
        );

        $tplData = array(); // $this->getTplData();
        $tplData['gen']    = $this;
        $tplData['levels'] = $levels;
        $tplData['ages']   = array('U10','U12','U14','U16','U19');
        
        $tplData['personId']      = $personId;
        $tplData['projectPerson'] = $projectPerson;
        
        return $this->render('ZaysoNatGamesBundle:Project:levels.html.twig',$tplData);
    }
    public function plansPostAction(Request $request, $id = 0)
    {
        $plansData = $request->request->get('plansData');
        if (!$plansData) $plansData = array();
        
        $projectPerson = $this->getProjectPerson();
        if (!$projectPerson) return $this->redirect($this->generateUrl('zayso_natgames_home'));

        $projectPerson->set('plans',$plansData);
        $this->getAccountManager()->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('zayso_natgames_project_plans'));
    }
    public function levelsPostAction(Request $request)
    {
        if ($id) $personId = $id;
        else     $personId = $this->getUser()->getPersonId();
        
        $manager = $this->get('zayso_core.account.home.manager');
        
        $projectPerson = $manager->addProjectPerson($this->getProjectId(),$personId);
        
        $levelsData = $request->request->get('levelsData');
        if (!$levelsData) $levelsData = array();
        
        $projectPerson->set('levels',$levelsData);
        $manager->flush();

        return $this->redirect($this->generateUrl('zayso_natgames_project_home'));
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
            'will_referee'     => $yesno,
            'do_assessments'   => $yesno,
            'coaching'         => $yesno,
            'other_jobs'       => $yesno,
            'ground_transport' => $yesno,
            
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
            't_shirt_size' => array
            (
                'NA'  => 'Select Size',
                'YM'  => 'Youth Medium',
                'YL'  => 'Youth Large',
                'AS'  => 'Adult Small',
                'AM'  => 'Adult Medium',
                'AL'  => 'Adult Large',
                'AXL' => 'Adult Large X',
                'A2X' => 'Adult Large XX',
                'A3X' => 'Adult Large XXX',
                'A4X' => 'Adult Large XXXX',
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
