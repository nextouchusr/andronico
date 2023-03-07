<?php
declare(strict_types=1);

namespace Hevelop\ImageRegenerator\Console\Command;

use Hevelop\ImageRegenerator\Model\ImageResize;
use Magento\Framework\App\Area;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\ObjectManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\ProgressBarFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Resizes product images according to theme view definitions.
 *
 * @package Magento\MediaStorage\Console\Command
 */
class ImagesResizeCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var State
     */
    private State $appState;

    /**
     * @var ProgressBarFactory
     */
    private $progressBarFactory;
    /**
     * @var ImageResize
     */
    private ImageResize $imageResize;

    /**
     * @param State $appState
     * @param ImageResize $imageResize
     * @param ObjectManagerInterface $objectManager
     * @param ProgressBarFactory $progressBarFactory
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        State $appState,
        ImageResize $imageResize,
        ObjectManagerInterface $objectManager,
        ProgressBarFactory $progressBarFactory = null
    ) {
        parent::__construct();
        $this->imageResize = $imageResize;
        $this->appState = $appState;
        $this->progressBarFactory = $progressBarFactory
            ?: ObjectManager::getInstance()->get(ProgressBarFactory::class);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('hevelop:images:resize')
            ->setDescription('Creates resized product images from custom params');
        $this->addArgument(
            'stores',
            \Symfony\Component\Console\Input\InputArgument::IS_ARRAY,
            'Stores. Example: "0 1 4 5"'
        );
        $this->addOption(
            'theme_path',
            'p',
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'Theme path. Example: "Colan/nextouch"'
        )->addOption(
            'quality',
            'u',
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'Image quality. Example: "95"'
        )->addOption(
            'overwrite',
            'o',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Overwrite image flag. Example: "1"'
        );
    }

    /**
     * @inheritdoc
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $errors = [];
            $this->appState->setAreaCode(Area::AREA_GLOBAL);

            $stores = ($input->hasArgument('stores')? $input->getArgument('stores'): []);
            $themePath = (string)($input->hasOption('theme_path')? $input->getOption('theme_path'): '');
            $quality = (int)($input->hasOption('quality')? $input->getOption('quality'): 0);
            $overWriteImages = (bool)($input->hasOption('overwrite')? $input->getOption('overwrite'): 0);

            if (!$themePath || !$quality || empty($stores)) {
                throw new \Symfony\Component\Console\Exception\InvalidArgumentException('missing required option');
            }

            $generator = $this->imageResize->resizeFromParams($themePath, $quality, $stores, $overWriteImages);

            /** @var ProgressBar $progress */
            $progress = $this->progressBarFactory->create(
                [
                    'output' => $output,
                    'max' => $generator->current()
                ]
            );
            $progress->setFormat(
                "%current%/%max% [%bar%] %percent:3s%% %elapsed% %memory:6s% \t| <info>%message%</info>"
            );

            if ($output->getVerbosity() !== OutputInterface::VERBOSITY_NORMAL) {
                $progress->setOverwrite(false);
            }

            while ($generator->valid()) {
                $resizeInfo = $generator->key();
                $error = $resizeInfo['error'];
                $filename = $resizeInfo['filename'];

                if ($error !== '') {
                    $errors[$filename] = $error;
                }

                $progress->setMessage($filename);
                $progress->advance();
                $generator->next();
            }
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            // we must have an exit code higher than zero to indicate something was wrong
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        $output->write(PHP_EOL);
        if (count($errors)) {
            $output->writeln("<info>Product images resized with errors:</info>");
            foreach ($errors as $error) {
                $output->writeln("<error>{$error}</error>");
            }
        } else {
            $output->writeln("<info>Product images resized successfully</info>");
        }

        return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }
}
