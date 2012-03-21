<?php
namespace Zayso\CoreBundle\Component\FormValidator;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Entity\Org;

class RegionValidator implements FormValidatorInterface
{
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function validate(FormInterface $form)
    {   
        // Org needs to be existing
        $orgId = $form['region']->getData();
        
        // The Datatransformer will blank out completely bogus values
        if (!$orgId)
        {
            $form['region']->addError(new FormError('Invalid region number.'));
            return;
        }
        // This will not automatically create one
        // $org = $this->em->getReference('ZaysoCoreBundle:Org',$orgId);
        
        // Sadly, this oads the entire record
        $org = $this->em->find('ZaysoCoreBundle:Org',$orgId);
        if (!$org) 
        {
            $org = new Org();
            $org->setId($orgId);
            $this->em->persist($org);
        }
        // Get entity and set the organization
        $accountPerson = $form->getData();
        $accountPerson->getPerson()->setOrg($org);
        return;
        
        die(get_class($accountPerson));
        $form['region']->addError(new FormError('Region changed.'));
        return;
    }
}

?>
