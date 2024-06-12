<?php

declare(strict_types = 1);

namespace Nextouch\ProductAttributeEAN\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Nextouch\ProductAttributeEAN\Model\Import as ImportModel;

class Import extends Command
{
    private $_importModel;
    private $_state;

    /**
     * @param State $state
     * @param ImportModel $importModel
     */
    public function __construct(
        State $state,
        ImportModel $importModel
    ){
        $this->_state = $state;
        $this->_importModel = $importModel;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('nextouch:ean:import');
        $this->setDescription('Import EAN product attribute from csv file.');
        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try{
            $output->writeln('<info>Command Start.</info>');
            $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            $this->_importModel->execute();
            $output->writeln('<info>Command Finished.</info>');
        }catch(\Exception $e){
            $output->writeln($e->getMessage());
        }finally{
            return 1;
        }
    }
}