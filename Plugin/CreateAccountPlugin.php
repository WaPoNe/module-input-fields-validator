<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Plugin;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\InputException;

/**
 * Class CreateAccountPlugin
 */
class CreateAccountPlugin extends AbstractInputFieldsValidator
{
    /**
     * @param AccountManagementInterface $subject
     * @param CustomerInterface $customer
     * @param $password
     * @param $redirectUrl
     *
     * @return void
     * @throws InputException
     */
    public function beforeCreateAccount(
        AccountManagementInterface $subject,
        CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    ) {
        if ($this->configurations->isModuleEnabled()) {
            try {
                $this->validateInput(
                    $customer->getFirstname(), 'First Name', $this->configurations->getCharactersLimit()
                );
                $this->validateInput(
                    $customer->getLastname(), 'Last Name', $this->configurations->getCharactersLimit()
                );
                $this->validateInput($customer->getEmail(), 'Email Address');
            } catch (InputException $e) {
                // Log the unsuccessful attempt
                $this->logAndNotify($e);
                // Rethrow the exception to stop the process
                throw $e;
            }
        }
    }
}
