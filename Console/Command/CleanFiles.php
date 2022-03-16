<?php

namespace MageSuite\ReportFileCleaner\Console\Command;

class CleanFiles extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\ReportFileCleaner\Helper\ConfigurationFactory
     */
    protected $configurationFactory;

    /**
     * @var \MageSuite\ReportFileCleaner\Service\LogFilesCleanerFactory
     */
    protected $logFilesCleanerFactory;

    /**
     * @param \MageSuite\ReportFileCleaner\Helper\ConfigurationFactory $configurationFactory
     * @param \MageSuite\ReportFileCleaner\Service\LogFilesCleanerFactory $logFilesCleanerFactory
     */
    public function __construct(
        \MageSuite\ReportFileCleaner\Helper\ConfigurationFactory $configurationFactory,
        \MageSuite\ReportFileCleaner\Service\LogFilesCleanerFactory $logFilesCleanerFactory
    ) {
        parent::__construct();
        $this->configurationFactory = $configurationFactory;
        $this->logFilesCleanerFactory = $logFilesCleanerFactory;
    }

    /** @inheritDoc */
    protected function configure()
    {
        $this->setName('system:clean_report_files')
            ->setDescription('Cleans report logs and other types of files');
    }

    /** @inheritDoc */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $configuration = $this->configurationFactory->create();
        if (!$configuration->isModuleEnabled()) {
            $output->writeln("Module is disabled, no action taken\n");
        }

        $logFilesCleaner = $this->logFilesCleanerFactory->create();
        $logFilesCleaner->cleanFiles();

        return true;
    }
}
