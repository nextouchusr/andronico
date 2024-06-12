<?php

declare(strict_types = 1);

namespace Nextouch\GestLogis\Console\Command;

use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Nextouch\GestLogis\Model\Import as ImportModel;

class Import extends Command
{
    private $_importModel;

    private $_state;

    /**
     * @param ImportModel $importModel
     * @param State $state
     */
    public function __construct(
        ImportModel $importModel,
        State $state
    ){
        $this->_importModel = $importModel;
        $this->_state = $state;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('nextouch:gestlogis:import');
        $this->setDescription('Import services and postcode excel sheet.');
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
