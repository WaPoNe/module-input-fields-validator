<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Utility;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Configurations
 */
class Configurations
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Configurations constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(Constants::MODULE_ENABLED_PATH);
    }

    /**
     * Get RegEx configuration
     */
    public function getRegEx()
    {
        return $this->scopeConfig->getValue(Constants::REGEX_PATH);
    }

    /**
     * Get Characters Limit configuration
     */
    public function getCharactersLimit()
    {
        return $this->scopeConfig->getValue(Constants::CHARACTERS_LIMIT_PATH);
    }

    /**
     * @return bool
     */
    public function isRegionValidationEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(Constants::REGION_ENABLED_PATH);
    }

    /**
     * @return bool
     */
    public function isNotificationsEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(Constants::NOTIFICATIONS_ENABLED_PATH);
    }

    /**
     * Get Email To configuration
     */
    public function getEmailTo()
    {
        return $this->scopeConfig->getValue(Constants::NOTIFICATIONS_EMAILS_PATH);
    }

    /**
     * Get Email From configuration
     */
    public function getEmailFrom()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Email From Name configuration
     */
    public function getEmailFromName()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general/name',
            ScopeInterface::SCOPE_STORE
        );
    }
}
