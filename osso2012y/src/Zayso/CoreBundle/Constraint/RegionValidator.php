<?php
namespace Zayso\CoreBundle\Component\FormValidator;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormValidatorInterface;

use Zayso\CoreBundle\Entity\Org;

class RegionValidator implements FormValidatorInterface
{
    public function __construct($em, $name='region')
    {
        $this->em = $em;
        $this->name = $name;
    }
    public function validate(FormInterface $form)
    {   
        $name = $this->name;
        
        // Org needs to be existing
        $orgId = $form[$name]->getData();
        
        // The Datatransformer will blank out completely bogus values
        if (!$orgId)
        {
            $form[$name]->addError(new FormError('Invalid region number.'));
            return;
        }
        // This will not automatically create one
        // $org = $this->em->getReference('ZaysoCoreBundle:Org',$orgId);
        
        // Sadly, this loads the entire record
        $org = $this->em->find('ZaysoCoreBundle:Org',$orgId);
        if (!$org) 
        {
            $org = new Org();
            $org->setId($orgId);
            $this->em->persist($org);
        }
        // Get entity and set the organization
        $item = $form->getData();
        $item->setOrg($org);
        return;
    }
}

?>
