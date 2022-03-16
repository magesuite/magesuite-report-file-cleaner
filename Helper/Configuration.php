<?php

namespace MageSuite\ReportFileCleaner\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const REPORT_FILE_CLEANER_ENABLED_XML_PATH = 'system/report_file_cleaner/enabled';
    public const STORING_FILES_PERIOD_IN_DAYS_XML_PATH = 'system/report_file_cleaner/storing_files_period_in_days';
    public const SEARCH_PATTERNS_XML_PATH = 'system/report_file_cleaner/search_patterns';

    /**
     * @var \MageSuite\ReportFileCleaner\Service\SearchPatternConverter
     */
    protected $searchPatternConverter;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \MageSuite\ReportFileCleaner\Service\SearchPatternConverter $searchPatternConverter
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MageSuite\ReportFileCleaner\Service\SearchPatternConverter $searchPatternConverter
    ) {
        parent::__construct($context);
        $this->searchPatternConverter = $searchPatternConverter;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->getValue(
            self::REPORT_FILE_CLEANER_ENABLED_XML_PATH
        );
    }

    /**
     * @return int
     */
    public function getStoringFilesPeriodInDays(): int
    {
        return (int) $this->scopeConfig->getValue(
            self::STORING_FILES_PERIOD_IN_DAYS_XML_PATH
        );
    }

    /**
     * @return array
     */
    public function getSearchPatterns(): array
    {
        $patterns = $this->scopeConfig->getValue(
            self::SEARCH_PATTERNS_XML_PATH
        );

        $searchPatterns = $this->searchPatternConverter->convertToArray($patterns);

        return $searchPatterns;
    }
}
