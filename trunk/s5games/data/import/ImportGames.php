<?php
class ImportGames
{
  protected $context;
  public    $count = 0;
  
  // G #	DATE	DIV	FIELD	TIME	R	TEAM H	HOME	TEAM A	AWAY
  protected $mapx = array
  (
    'G #'    => 'game_num',
    'DATE'   => 'game_date',
    'TIME'   => 'game_time',
    'DIV'    => 'game_div',
    'FIELD'  => 'game_field',
    'R'      => 'game_type',
    'TEAM H' => 'home_bracket',
    'HOME'   => 'home_name',
    'TEAM A' => 'away_bracket',
    'AWAY'   => 'away_name',
  );
  protected $map = array
  (
    0   => 'game_num',
    1   => 'game_date',
    2   => 'game_div',
    3   => 'game_time',
    4   => 'game_field',
    5   => 'game_type',
    6   => 'game_bracket',
    7   => 'home_name',
    8   => 'away_name'
  );
  protected $fields = array(
    'JH1'  => 'JH 1',
    'JH2'  => 'JH 2',
    'JH3'  => 'JH 3',
    'JH4'  => 'JH 4',
    'JH5'  => 'JH 5',
    'JH6'  => 'JH 6',
    'JH7'  => 'JH 7',
    'JH8'  => 'JH 8',
    'JH9'  => 'JH 9',
    'JH10' => 'JH 5',  // Mistake in original schedule
    'JH5 / MM5' => 'JH 5*',
    'MM1'  => 'MM 1',
    'MM2'  => 'MM 2',
    'MM3'  => 'MM 3',
    'MM4'  => 'MM 4',
    'MM5'  => 'MM 5',
    'MM6'  => 'MM 6',
    'MM7'  => 'MM 7',
    'MM8'  => 'MM 8',
    'MM9'  => 'MM 9',
    'MM10' => 'MM10',    
  );
  public function __construct($context,$fileName = NULL)
  {
    $this->context = $context;
    $this->init();
    if ($fileName) $this->process($fileName);
  }
  protected function init()
  {
  }
  public function process($fileName)
  {
    $fp = fopen($fileName,'r');
    while($row = fgetcsv($fp,1000))
    {
      // Cerad_Debug::dump($row); die();
      $this->processRow($row);
    }
    fclose($fp);
  }
  protected function processRow($row)
  {
    if (count($row) < 9) return;
    $item = array();
    foreach($this->map as $index => $name)
    {
      $item[$name] = $row[$index];
    }
    // Cerad_Debug::dump($item); die();
    $this->processRowData($item);
  }
  public function processRowData($data)
  {
    // Validation
    $valid = TRUE;
    if (!$data['game_num'])
    {
      return;
    }
    // Clean up time for sorting
    if (strlen($data['game_time']) == 3) $data['game_time'] = '0' . $data['game_time'];
        
    // Clean up field for sorting
    if (!isset($this->fields[$data['game_field']]))
    {
      die($data['game_num'] . ' ' . $data['game_field']);
    }
    $data['game_div'] = 'U' . $data['game_div'];
    
    $data['game_field'] = $this->fields[$data['game_field']];
  //Cerad_Debug::dump($data); die();
		
    // And store
    $db = $this->context->getDb();
    $db->insert('games','game_num',$data);
    $this->count++;
  }
}
?>
