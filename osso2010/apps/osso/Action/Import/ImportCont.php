<?php
class Action_Import_ImportCont extends Action_Base_BaseCont
{
  protected $tplTitle = 'OSSO Import';
  protected $tplName  = 'Action/Import/Import.html.php';

  public function executeGet()
  {
    $context = $this->context;

    $sessionData = $context->session->get('import');
    if (!$sessionData) $sessionData = $context->getSessionData();

    $this->importClassName = $sessionData->className;
    $this->importFileName  = $sessionData->fileName;
    $this->importErrors    = $sessionData->errors;
    $this->importResults   = $sessionData->results;

    $this->sourceOptions = array
    (
      1 => 'eayso',
      2 => 'manual',
    );

    return $this->renderPage();
    
  }
  // Used to determine type of file
  protected $types = array
  (
    'Eayso_Import_VolCert' => array('Region','FirstName','LastName','CertificationDesc','Certification Date'),
    'Eayso_Import_Vol'     => array('AYSOID','suffix','Membershipyear'),
  );
  protected function getImportClassName($header)
  {
    foreach($this->types as $className => $columns)
    {
      $match = TRUE;
      foreach($columns as $column)
      {
        if (array_search($column,$header) === FALSE) $match = FALSE;
      }
      if ($match) return $className;
    }
    return NULL;
  }
  public function executePost()
  {
    //Cerad_Debug::dump($_FILES);
    //Cerad_Debug::dump($_POST);
    //die();

    $redirect = '?la=import';

    $session     = $this->context->session;
    $sessionData = $this->context->getSessionData();
    $errors = NULL;

    // Information from the _FILES structure
    $files = $this->context->requestFiles;
    $info  = $files->get('file');
    if (!isset($info['tmp_name']))
    {
      $session->set('import',$sessionData);
      return $this->redirect($redirect);
    }

    $sessionData->tmpName  = $tmpName  = $info['tmp_name'];
    $sessionData->fileName = $fileName = $info['name'];
    $sessionData->fileType = $fileType = $info['type'];

    if ($fileType != 'text/csv')
    {
      $errors[] = "File type is not csv for {$fileName} {$type}";
      $sessionData->errors = $errors;
      $session->set('import',$sessionData);
      return $this->redirect($redirect);
    }
    $fp = fopen($tmpName,'r');
    $header = fgetcsv($fp);
    fclose($fp);

    $sessionData->className = $className = $this->getImportClassName($header);

    if (!$className)
    {
      $errors[] = "Could not determine import class for {$fileName}";
      $sessionData->errors = $errors;
      $session->set('import',$sessionData);
      return $this->redirect($redirect);
    }
    $import = new $className($this->context);
    $import->process($tmpName);
    
    $sessionData->results = $import->getResultMessage();

    // Done
    $session->set('import',$sessionData);
    return $this->redirect($redirect);

    // Cerad_Debug::dump($header);
  }
}
?>
