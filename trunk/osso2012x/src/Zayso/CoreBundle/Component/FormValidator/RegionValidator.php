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
        $org   = $this->em->find('ZaysoCoreBundle:Org',$orgId);
        if (!$org) 
        {
            // Make sure have something entered
            if (!$orgId)
            {
                $form['region']->addError(new FormError('Region does not exist.'));
                return;
            }
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
