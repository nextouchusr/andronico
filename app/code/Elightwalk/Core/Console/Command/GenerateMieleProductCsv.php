<?php

declare(strict_types=1);

namespace Elightwalk\Core\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Elightwalk\Core\Model\GenerateMieleProductCsv as ModelGenerateMieleProductCsv;

/**
 * GenerateMieleProductCsv class
 * @package Elightwalk\Core\Console\Command
 */
class GenerateMieleProductCsv extends Command
{
    /**
     * @var ModelGenerateMieleProductCsv
     */
    private $_modelGenerateMieleProductCsv;

    /**
     * @var State
     */
    private $_state;

    /**
     * __construct function
     *
     * @param ModelGenerateMieleProductCsv $modelGenerateMieleProductCsv
     * @param State $state
     * @param string $name
     */
    public function __construct(
        ModelGenerateMieleProductCsv $modelGenerateMieleProductCsv,
        State $state,
        string $name = null

    ) {
        $this->_modelGenerateMieleProductCsv = $modelGenerateMieleProductCsv;
        $this->_state = $state;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('generate:miele:product:csv');
        $this->setDescription('Command for generate miele product CSV.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_state->setAreaCode(Area::AREA_ADMINHTML);
        $output->writeln('<info>START GENERATE CSV</info>');
        $this->_modelGenerateMieleProductCsv->execute();
        $output->writeln('<info>FINISH GENERATE CSV</info>');
        return null;
    }
}
