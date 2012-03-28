<?php
namespace Zayso\ArbiterBundle\Controller\Test1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

use Zayso\CoreBundle\Component\Debug;

class SendMailType extends AbstractType
{

    /**
     * @var Symfony\Component\Security\Core\SecurityContext
     */
    protected $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('recipient', 'text',     array('required' => false))
            ->add('textx',     'textarea', array('required' => false));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'validation_constraint' => new Collection(array(
                'allowExtraFields' => true,
                'fields' => array('recipient' => array(new NotBlank())),
            ))
        );
    }

    public function getName() { return 'send_mail'; }
}

class MainController extends Controller
{
    public function indexAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:content.html.twig',$tplData);
    }
    public function sidebarAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:sidebar.html.twig',$tplData);
    }
    public function sidebarjsAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:sidebarjs.html.twig',$tplData);
    }
    public function test2Action(Request $request)
    {
        $context = $this->get('security.context');
        $form    = $this->createForm(new SendMailType($context));
        $msg = null;
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $msg = 'Form is valid';
            }
            else $msg = 'Form is not valid';
        }
        
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['msg']  = $msg;

        $vars = $tplData['form']->getVars();
        print_r($vars); die();
        return $this->render('ZaysoArbiterBundle:Test2:form.html.twig',$tplData);
        
    }
    
}
?>
