<?php

namespace Zayso\S5GamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Zayso\CoreBundle\Component\DataTransformer\StripTagsTransformer;
use Zayso\CoreBundle\Controller\BaseController;

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
        $builder->add('willAttend', 'choice', array(
            'label'         => 'I plan on attending the games',
            'required'      => true,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('willReferee', 'choice', array(
            'label'         => 'I will referee during the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('willAssess', 'choice', array(
            'label'         => 'I will do assessments/observations during the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('wantAssess', 'choice', array(
            'label'         => 'Request observation/assessment',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->wantAssessChoices,
        ));
        $builder->add('willCoach', 'choice', array(
            'label'         => 'I will coach or manage a team',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('willVolunteer', 'choice', array(
            'label'         => 'I will volunteer during the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('havePlayer', 'choice', array(
            'label'         => 'I have a player in the games',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->yesNoPickList,
        ));
        $builder->add('tshirtSize', 'choice', array(
            'label'         => 'T-Shirt Size',
            'required'      => false,
            'empty_value'   => 'Select Answer',
            'choices'       => $this->tshirtSizePickList,
        ));
        $builder->add('notes', 'textarea', array('label' => 'Notes', 'required' => false, 
            'attr' => array('rows' => 4, 'cols' => 80, 'wrap' => 'hard', 'class' =>'textarea')));
        
        $builder->get('notes')->appendClientTransformer(new StripTagsTransformer());
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
            'willAttend'    => null,
            'willReferee'   => null,
            'willAssess'    => null,
            'requestAssess' => null,
            'willCoach'     => null,
            'havePlayer'    => null,
            'willVolunteer' => null,
            'tshirtSize'    => null,
            'notes'         => null,
        );
        
        // Load the project person, create one of needed
        if ($id) $personId = $id;
        else     $personId = $this->getUser()->getPersonId();
        
        $manager = $this->getAccountManager();
        
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
                
                return $this->redirect($this->generateUrl('zayso_core_home'));
            }
        }
        
        $tplData = array();
        $tplData['personId'] = $personId;
        $tplData['form'] = $form->createView();

        return $this->renderx('Project:plans.html.twig',$tplData);
    }

}
