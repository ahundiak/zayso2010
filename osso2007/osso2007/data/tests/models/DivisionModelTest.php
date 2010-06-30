<?php
class DivisionModelTest extends BaseModelTest
{
    function testAgeRange()
    {
        $model = $this->context->models->DivisionModel;
        
        $divs = $model->getDivisionIdsForAgeRange(13,14,TRUE,TRUE,FALSE);

        $this->assertEquals($divs,array(
            'key13B' => 13, 
            'key13G' => 14,
        ));
                
        $divs = $model->getDivisionIdsForAgeRange(12,14,TRUE,FALSE,TRUE);
        
        $this->assertEquals($divs,array(
            'key10B' => 10, 
            'key10C' => 12,
            'key13B' => 13, 
            'key13C' => 15,
        ));
        //Zend::dump($divs);
    }
}

?>
