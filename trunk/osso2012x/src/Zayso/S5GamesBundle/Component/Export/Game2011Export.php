<?php
namespace Zayso\S5GamesBundle\Component\Export;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;


class Game2011Export
{
    protected $excel = null;
    protected $manager = null;
    
    protected $gamesIndex = 0;
   
    protected $games = null;
    
    public function __construct($manager,$excel)
    {
        $this->manager   = $manager;
        $this->excel     = $excel;
    }
    public function getGames()
    {
        if ($this->games) return $this->games;
        $this->games = array();
        
        $sql = 'SELECT * FROM s5games.games ORDER BY game_num';

        $db = $this->manager->getEntityManager()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $this->games = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $this->games;
        
        Debug::dump($games[0]);
        die('Game count: ' . count($games));
    }
    protected function generateGames($ss)
    {
        $map = array(
            'Game'      => 'game_num',
            'Date'      => 'game_date',
            'Time'      => 'game_time',
            'Div'       => 'game_div',
            'Field'     => 'game_field',
            'Type'      => 'game_type',
            'Bracket'   => 'game_bracket',
            'Home Team' => 'home_name',
            'Away Team' => 'away_name',
        );
        $ws = $ss->setActiveSheetIndex($this->gamesIndex);
        $ws->setTitle('Games');
        
        $row = 1;
        $col = 0;
        foreach(array_keys($map) as $header)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$header);
        }

        $items = $this->getGames();
        foreach($items as $item)
        {
            $row++;
            $col = 0;
            foreach($map as $propName)
            {
                $ws->setCellValueByColumnAndRow($col++,$row,$item[$propName]);
            }
        }
    }
    public function generate()
    {
        $excel = $this->excel;
        
        $ss = $excel->newSpreadSheet();

        $this->generateGames($ss);
        
        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();

    }
}

?>
