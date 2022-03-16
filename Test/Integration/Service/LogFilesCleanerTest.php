<?php

namespace MageSuite\ReportFileCleaner\Test\Integration\Service;

class LogFilesCleanerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\ReportFileCleaner\Service\LogFilesCleaner
     */
    protected $logFilesCleaner;

    /**
     * @var \MageSuite\ReportFileCleaner\Helper\Directory
     */
    protected $directory;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driverFile;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->logFilesCleaner = $this->objectManager->create(\MageSuite\ReportFileCleaner\Service\LogFilesCleaner::class);
        $this->directory = $this->objectManager->create(\MageSuite\ReportFileCleaner\Helper\Directory::class);
        $this->driverFile = $this->objectManager->create(\Magento\Framework\Filesystem\Driver\File::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadDirectoriesAndFilesFixture
     */
    public function testItRemovesFiles()
    {
        $varDirectoryPath = $this->directory->getDirectoryAbsolutePath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $varDirectoryFiles = $this->driverFile->readDirectory($varDirectoryPath);

        $reportDirectoryPath = sprintf('%s/report', $varDirectoryPath);

        $this->assertContains($reportDirectoryPath, $varDirectoryFiles);

        $reportDirectoryFiles = $this->driverFile->readDirectory($reportDirectoryPath);

        $this->assertCount(4, $reportDirectoryFiles);

        $this->logFilesCleaner->cleanFiles();
        $reportDirectoryFilesAfterCleaning = $this->driverFile->readDirectory($reportDirectoryPath);

        $this->assertCount(0, $reportDirectoryFilesAfterCleaning);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/system/report_file_cleaner/storing_files_period_in_days 15
     * @magentoDataFixture loadDirectoriesAndFilesFixture
     */
    public function testItDoesNotRemoveFilesWhenStoragePeriodNotPassed()
    {
        $varDirectoryPath = $this->directory->getDirectoryAbsolutePath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $varDirectoryFiles = $this->driverFile->readDirectory($varDirectoryPath);

        $reportDirectoryPath = sprintf('%s/report', $varDirectoryPath);

        $this->assertContains($reportDirectoryPath, $varDirectoryFiles);

        $reportDirectoryFiles = $this->driverFile->readDirectory($reportDirectoryPath);

        $this->assertCount(4, $reportDirectoryFiles);

        $this->logFilesCleaner->cleanFiles();
        $reportDirectoryFilesAfterCleaning = $this->driverFile->readDirectory($reportDirectoryPath);

        $this->assertCount(4, $reportDirectoryFilesAfterCleaning);
    }

    public static function loadDirectoriesAndFilesFixture()
    {
        require __DIR__ . '/../_files/directories_and_files.php';
    }
}
