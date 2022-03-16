<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\Filesystem\Driver\File $driverFile */
$driverFile = $objectManager->create(\Magento\Framework\Filesystem\Driver\File::class);

/** @var \MageSuite\ReportFileCleaner\Helper\Directory $directory */
$directory = $objectManager->create(\MageSuite\ReportFileCleaner\Helper\Directory::class);

$varDirectoryPath = $directory->getDirectoryAbsolutePath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);

$reportDirectoryPath = sprintf('%s/report', $varDirectoryPath);
$driverFile->createDirectory($reportDirectoryPath);

if ($driverFile->isDirectory($reportDirectoryPath)) {
    $testFilePath = sprintf('%s/test_file.txt', $reportDirectoryPath);
    $testFile = $driverFile->fileOpen($testFilePath, 'w');
    $driverFile->fileWrite($testFile, '');

    $logsDirectoryPath = sprintf('%s/logs', $reportDirectoryPath);
    $driverFile->createDirectory($logsDirectoryPath);

    $sampleFilePath = sprintf('%s/sample_file.txt', $reportDirectoryPath);
    $sampleFile = $driverFile->fileOpen($sampleFilePath, 'w');
    $driverFile->fileWrite($sampleFile, '');

    $sampleReportsDirectoryPath = sprintf('%s/sample_reports', $reportDirectoryPath);
    $driverFile->createDirectory($sampleReportsDirectoryPath);

    $sampleFile2Path = sprintf('%s/sample_file_2.txt', $logsDirectoryPath);
    $sampleFile2 = $driverFile->fileOpen($sampleFile2Path, 'w');
    $driverFile->fileWrite($sampleFile2, '');
}
