<?php

namespace App\Command;

use App\Service\WorklogExtractor;
use App\Service\WorklogTransformer;
use App\Service\WorklogLoader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorklogMigrateCommand extends Command
{
    protected static $defaultName = 'worklog:migrate';

    /**
     * @var WorklogExtractor
     */
    private $extractor;

    /**
     * @var WorklogTransformer
     */
    private $transformer;

    /**
     * @var WorklogLoader
     */
    private $loader;


    public function __construct(WorklogExtractor $extractor, WorklogTransformer $transformer, WorklogLoader $loader)
    {
        $this->extractor = $extractor;
        $this->transformer = $transformer;
        $this->loader = $loader;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Migrate Worklogs')
            ->setHelp('This command allows you to migrate worklogs.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $products = $this->extractor->extract();

        foreach ($products as $productData) {

            try {
                $product = $this->transformer->transform($productData);
                //print_r($product);
                $this->loader->load($product);
                
                $output->writeln('<info>[Success] ['.$product->getName().'] Item migrated</info>');
            } catch (\Exception $e) {
                $output->writeln('<error>[Failure] ['.$product->getName().'] '.$e->getMessage().'</error>');
            }
        }

        return Command::SUCCESS;

    }
}