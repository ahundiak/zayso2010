<?php
use Entity\Page;
use Entity\PageBasket;
use Entity\Comments;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class MyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('mine')
            ->setDescription('My Command')
            ->setHelp('Help is for sissies')
        ;
    }
    protected function getEntityManager()
    {
        return $this->getHelper('em')->getEntityManager();
    }
    protected function testInsert()
    {
        $em = $this->getEntityManager();
        
        $page = new Page();
        $pageBasket = new PageBasket();
        $pageBasket->setpage($page);
        
        $em->persist($page);
        $em->persist($pageBasket);
        $em->flush();
        
        echo "Added entities\n";
    }
    protected function testQuery()
    {
        $basketId = 1;
        
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->addSelect('page');
        $qb->addSelect('pageBasket');
        
        $qb->from('\Entity\Page','page');
        $qb->leftJoin('page.pageBaskets','pageBasket');
        
        $qb->andWhere($qb->expr()->in('pageBasket.id',$basketId));
        
        $query = $qb->getQuery();
        $results = $query->getResult();
        
        $page = $results[0];
        $pageBaskets = $page->getPageBaskets();
        $pageBasket = $pageBaskets[0];
        
        echo 'Result Count ' . count($results) . "\n";
        echo 'Page ID ' . $page->getId() . "\n";
        echo 'Page Basket ID ' . $pageBasket->getId() . "\n";
        echo $query->getSQL() . "\n";
    }
    // Doesent seem to use proxies?
    protected function testLazyLoad()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('Entity\Page');
        $pages = $repo->findAll();
        echo 'Page Count ' . count($pages) . "\n";
        foreach($pages as $page)
        {
            echo get_class($page) . "\n";
            $pageBaskets = $page->getPageBaskets();
            foreach($pageBaskets as $pageBasket)
            {
                echo get_class($pageBasket) . "\n";
            }
        }
        
    }
    protected function testJoinedInsert()
    {
        $em = $this->getEntityManager();
        $comment = new Comments();
        $comment->setComment("my new comment");
        
        $em->persist($comment);
        $em->flush();   
    }
    protected function testJoinedQuery()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('Entity\Comments');
        
        $entity = $repo->findOneBy(array('id' => 2));
        
        echo get_class($entity) . ' ' . $entity->getComment() . "\n";
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testJoinedInsert();
        $this->testJoinedQuery();
    }
}
?>
