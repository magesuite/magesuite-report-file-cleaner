<?php

namespace MageSuite\ReportFileCleaner\Service;

class LogFilesCleaner
{
    /**
     * @var \MageSuite\ReportFileCleaner\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\ReportFileCleaner\Helper\Directory
     */
    protected $directory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \MageSuite\ReportFileCleaner\Helper\Configuration $configuration
     * @param \MageSuite\ReportFileCleaner\Helper\Directory $directory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \MageSuite\ReportFileCleaner\Helper\Configuration $configuration,
        \MageSuite\ReportFileCleaner\Helper\Directory $directory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->configuration = $configuration;
        $this->directory = $directory;
        $this->logger = $logger;
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function cleanFiles()
    {
        $period = $this->configuration->getStoringFilesPeriodInDays();
        $searchPatterns = $this->configuration->getSearchPatterns();

        foreach ($searchPatterns as $searchPattern) {
            $this->cleanFilesForPattern($searchPattern, $period);
        }
    }

    /**
     * @param string $pattern
     * @param int|null $period
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function cleanFilesForPattern(string $searchPattern, ?int $period)
    {
        $pattern = sprintf('%s/%s', $this->directory->getDirectoryAbsolutePath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR), ltrim($searchPattern, '/'));
        $filesPaths = $this->directory->getItemsForPattern($pattern);
        $this->removeFiles($filesPaths, $period);
    }

    /**
     * @param array $filesPaths
     * @param int|null $period
     */
    public function removeFiles(array $filesPaths, ?int $period = null)
    {
        foreach ($filesPaths as $filePath) {
            try {
                $file = new \SplFileInfo($filePath);
                $creationTime = $file->getCTime();
                $creationDate = new \DateTime(date('Y-m-d H:i:s', $creationTime));
                $now = new \DateTime('now');
                $interval = (int)$now->diff($creationDate)->format('%a');
                if (!$period || $interval > $period) {
                    $this->directory->deleteItem($file);
                }
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage(), ['filepath' => $filePath]);
            }
        }
    }
}
