<?php

namespace MageSuite\ReportFileCleaner\Model\Config;

class StoredFilesComment implements \Magento\Config\Model\Config\CommentInterface
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
     * @param \MageSuite\ReportFileCleaner\Helper\Configuration $configuration
     * @param \MageSuite\ReportFileCleaner\Helper\Directory $directory
     */
    public function __construct(
        \MageSuite\ReportFileCleaner\Helper\Configuration $configuration,
        \MageSuite\ReportFileCleaner\Helper\Directory $directory
    ) {
        $this->configuration = $configuration;
        $this->directory = $directory;
    }

    /**
     * @param string $elementValue
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getCommentText($elementValue)
    {
        $comment = 'The base directory is var/<br/>All patterns should be paths inside var/ directory.';

        $searchPatterns = $this->configuration->getSearchPatterns();

        if (!$searchPatterns) {
            return $comment;
        }

        $comment = sprintf('%s<br/><br/>Currently stored files amount: <br/><br/>', $comment);
        foreach ($searchPatterns as $searchPattern) {
            $pattern = sprintf('%s/%s', $this->directory->getDirectoryAbsolutePath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR), ltrim($searchPattern, '/'));
            $filesNumber = count($this->directory->getFilesForPattern($pattern));
            $comment .= sprintf('%s: %d<br/>', $pattern, $filesNumber);
        }

        return $comment;
    }
}
