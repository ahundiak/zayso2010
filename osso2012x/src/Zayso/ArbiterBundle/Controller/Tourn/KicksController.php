<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\UssfidTransformer;

class KicksController extends Controller
{
    public function signupAction(Request $request)
    {
        $form = array(
            'firstName' => array('name' => 'firstName', 'type' => 'text', 'label' => 'First Name'),
            'lastName'  => array('name' => 'lastName',  'type' => 'text', 'label' => 'Last Name'),
        );
        $tplData = array();
        $tplData['form'] = $form;
        return $this->render('ZaysoArbiterBundle:Tourn\Kicks:form.html.twig',$tplData);
    }
}
?>
