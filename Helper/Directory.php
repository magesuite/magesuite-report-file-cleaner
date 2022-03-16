<?php

namespace MageSuite\ReportFileCleaner\Helper;

class Directory
{
    /**
     * @var \MageSuite\ReportFileCleaner\Helper\SearchPattern
     */
    protected $searchPattern;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driverFile;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @param SearchPattern $searchPattern
     * @param \Magento\Framework\Filesystem\Driver\File $driverFile
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \MageSuite\ReportFileCleaner\Helper\SearchPattern $searchPattern,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->searchPattern = $searchPattern;
        $this->driverFile = $driverFile;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $searchPattern
     * @param bool $recursiveSearch
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getItemsForPattern(string $searchPattern, bool $recursiveSearch = false): array
    {
        $patternFileName = $this->searchPattern->getPatternFileName($searchPattern);
        $patternDirectoryPath = $this->searchPattern->getPatternDirectoryPath($searchPattern);

        if ($recursiveSearch) {
            $paths = $this->searchItems($patternFileName, $patternDirectoryPath);
        } else {
            $paths = $this->driverFile->search($patternFileName, $patternDirectoryPath);
        }

        return $paths;
    }

    /**
     * @param string $pattern
     * @param string $path
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function searchItems(string $pattern, string $path): array
    {
        $matchedPaths = $this->driverFile->search($pattern, $path);
        $directoriesPaths = \Magento\Framework\Filesystem\Glob::glob(sprintf('%s/%s', $path, $pattern), \Magento\Framework\Filesystem\Glob::GLOB_ONLYDIR);

        foreach ($directoriesPaths as $directoryPath) {
            $matchedPaths = array_merge($matchedPaths, $this->searchItems($pattern, $directoryPath));// phpcs:ignore
        }

        return $matchedPaths;
    }

    /**
     * @param string $searchPattern
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getFilesForPattern(string $searchPattern): array
    {
        $allItems = $this->getItemsForPattern($searchPattern, true);
        $directories = $this->getDirectoriesForPattern($searchPattern, true);

        return array_diff($allItems, $directories);
    }

    /**
     * @param string $searchPattern
     * @return array
     */
    public function getDirectoriesForPattern(string $searchPattern, bool $recursiveSearch = false): array
    {
        if ($recursiveSearch) {
            $directories = $this->searchDirectories($searchPattern);
        } else {
            $directories = \Magento\Framework\Filesystem\Glob::glob($searchPattern, \Magento\Framework\Filesystem\Glob::GLOB_ONLYDIR);
        }

        return $directories;
    }

    /**
     * @param string $pattern
     * @return array
     */
    public function searchDirectories(string $pattern): array
    {
        $matchedPaths = [];
        $directoriesPaths = \Magento\Framework\Filesystem\Glob::glob($pattern, \Magento\Framework\Filesystem\Glob::GLOB_ONLYDIR);
        foreach ($directoriesPaths as $directoryPath) {
            $matchedPaths[] = $directoryPath;
            $patternFileName = $this->searchPattern->getPatternFileName($pattern);
            $newPattern = sprintf('%s/%s', $directoryPath, $patternFileName);
            $matchedPaths = array_merge($matchedPaths, $this->searchDirectories($newPattern));// phpcs:ignore
        }

        return $matchedPaths;
    }

    /**
     * @param string $path
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function deleteItem(string $path): void
    {
        if ($this->driverFile->isFile($path)) {
            $this->driverFile->deleteFile($path);
        } else {
            $this->driverFile->deleteDirectory($path);
        }
    }

    /**
     * @param string $directoryCode
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getDirectoryAbsolutePath(string $directoryCode): string
    {
        return rtrim($this->filesystem->getDirectoryWrite($directoryCode)->getAbsolutePath(), '/');
    }
}
