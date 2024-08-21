<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Utility;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Configurations
 */
readonly class Configurations
{
    /**
     * Configurations constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        public ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(Constants::MODULE_ENABLED_PATH);
    }

    /**
     * @return mixed
     */
    public function getRegEx(): mixed
    {
        return $this->scopeConfig->getValue(Constants::REGEX_PATH);
    }

    /**
     * @return mixed
     */
    public function getCharactersLimit(): mixed
    {
        return $this->scopeConfig->getValue(Constants::CHARACTERS_LIMIT_PATH);
    }

    /**
     * @return bool
     */
    public function isNotificationsEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(Constants::NOTIFICATIONS_ENABLED_PATH);
    }

    /**
     * @return mixed
     */
    public function getEmailTo(): mixed
    {
        return $this->scopeConfig->getValue(Constants::NOTIFICATIONS_EMAILS_PATH);
    }

    /**
     * @return mixed
     */
    public function getEmailFrom(): mixed
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getEmailFromName(): mixed
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general/name',
            ScopeInterface::SCOPE_STORE
        );
    }
}
