<?php

namespace MageSuite\ReportFileCleaner\Plugin\Config\Model\Config;

class ValidateSearchPatterns
{
    /**
     * @var \MageSuite\ReportFileCleaner\Service\SearchPatternConverter
     */
    protected $searchPatternConverter;

    /**
     * @param \MageSuite\ReportFileCleaner\Service\SearchPatternConverter $searchPatternConverter
     */
    public function __construct(
        \MageSuite\ReportFileCleaner\Service\SearchPatternConverter $searchPatternConverter
    ) {
        $this->searchPatternConverter = $searchPatternConverter;
    }

    /**
     * @param \Magento\Config\Model\Config $subject
     */
    public function beforeSave(\Magento\Config\Model\Config $subject)
    {
        $data = $subject->getData();
        if (!isset($data['groups']['report_file_cleaner']['fields']['search_patterns']['value'])) {
             return [];
        }

        $searchPatterns = $this->searchPatternConverter->convertToArray($data['groups']['report_file_cleaner']['fields']['search_patterns']['value']);
        foreach ($searchPatterns as $key => $pattern) {
            if ((strpos($pattern, '../') !== false) || (strpos($pattern, './') !== false)) {
                $searchPatterns[$key] = str_replace(['../', './'], '', $pattern);
            }
        }

        $patterns = $this->searchPatternConverter->convertToString($searchPatterns);
        $data['groups']['report_file_cleaner']['fields']['search_patterns']['value'] = $patterns;
        $subject->setData($data);
    }
}
