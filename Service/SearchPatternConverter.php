<?php

namespace MageSuite\ReportFileCleaner\Service;

class SearchPatternConverter
{
    /**
     * @param string $patterns
     * @return array
     */
    public function convertToArray(string $patterns): array
    {
        $searchPatterns = preg_split('/\n|\r\n?/', $patterns);

        if ($searchPatterns === false) {
            return [];
        }

        $searchPatterns = array_filter($searchPatterns, function ($pattern) {
            return strlen(trim($pattern));
        });

        return $searchPatterns;
    }

    /**
     * @param array $patterns
     * @return string
     */
    public function convertToString(array $patterns): string
    {
        return implode("\n", $patterns);
    }
}
