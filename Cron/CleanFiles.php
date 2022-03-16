<?php

namespace MageSuite\ReportFileCleaner\Cron;

class CleanFiles
{
    /**
     * @var \MageSuite\ReportFileCleaner\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\ReportFileCleaner\Service\LogFilesCleaner
     */
    protected $logFilesCleaner;

    /**
     * @param \MageSuite\ReportFileCleaner\Helper\Configuration $configuration
     * @param \MageSuite\ReportFileCleaner\Service\LogFilesCleaner $logFilesCleaner
     */
    public function __construct(
        \MageSuite\ReportFileCleaner\Helper\Configuration $configuration,
        \MageSuite\ReportFileCleaner\Service\LogFilesCleaner $logFilesCleaner
    ) {
        $this->configuration = $configuration;
        $this->logFilesCleaner = $logFilesCleaner;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        if (!$this->configuration->isModuleEnabled()) {
            return;
        }

        $this->logFilesCleaner->cleanFiles();

        return true;
    }
}
