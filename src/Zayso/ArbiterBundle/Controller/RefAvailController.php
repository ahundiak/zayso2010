<?php
namespace Zayso\ArbiterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefAvailController extends BaseController
{
    public function indexAction(Request $request)
    {
        $attachment = $request->files->get('ref_avail_file');
        
        $inputFileName  = $attachment->getPathName(); // /var/tmp/whatever
        $clientFileName = $attachment->getClientOriginalName();

        $inputFileNamex = $inputFileName . 'x';
        $outFileName = basename($clientFileName,'.csv') . 'x.csv';
        
        $manager = $this->get('zayso_arbiter.ref_avail.process');
        
        $manager->importCSV($inputFileName);
        $manager->exportCSV($inputFileNamex);
        
        $response = new Response(file_get_contents($inputFileNamex));
        
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'. $outFileName .'"');

        // Might want to unlink the generated files
        //unlink($inputFileName);
        //unlink($inputFileNamex);
        
        return $response;
    }
}
?>
