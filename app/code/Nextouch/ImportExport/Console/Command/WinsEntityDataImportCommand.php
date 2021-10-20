<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\ImportExport\Model\WinsEntityDataOperationCombiner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WinsEntityDataImportCommand extends Command
{
    private const NAME = 'wins:entity-data:import';

    private WinsEntityDataOperationCombiner $entityDataOperationCombiner;
    private State $state;

    public function __construct(WinsEntityDataOperationCombiner $entityDataOperationCombiner, State $state)
    {
        parent::__construct();
        $this->entityDataOperationCombiner = $entityDataOperationCombiner;
        $this->state = $state;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Run Wins entity data operations to import files from ERP');
    }

    /**
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Running Wins entity data operations...</info>');

        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        $this->entityDataOperationCombiner->run();

        $output->writeln('<info>Wins entity data operations completed!</info>');

        return 0;
    }
}
