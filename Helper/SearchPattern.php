<?php

namespace MageSuite\ReportFileCleaner\Helper;

class SearchPattern
{
    /**
     * @param string $pattern
     * @return string
     */
    public function getPatternDirectoryPath(string $pattern): string
    {
        $searchPatternParts = $this->getSearchPatternParts($pattern);
        array_pop($searchPatternParts);
        $directoryPath = sprintf('/%s', implode('/', $searchPatternParts));

        return $directoryPath;
    }

    /**
     * @param string $pattern
     * @return string
     */
    public function getPatternFileName(string $pattern): string
    {
        $searchPatternParts = $this->getSearchPatternParts($pattern);
        $fileNamePattern = array_pop($searchPatternParts);

        return $fileNamePattern;
    }

    /**
     * @param string $searchPattern
     * @return array
     */
    protected function getSearchPatternParts(string $searchPattern): array
    {
        $pattern = trim($searchPattern, '/');
        $searchPatternParts = explode('/', $pattern);

        return $searchPatternParts;
    }
}
