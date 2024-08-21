<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Plugin;

use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\InputException;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResourceModel;

/**
 * Class CustomerSavePlugin
 */
class CustomerSavePlugin extends AbstractInputFieldsValidator
{
    /**
     * @param CustomerResourceModel $subject
     * @param Customer $customer
     *
     * @return void
     * @throws InputException
     */
    public function beforeSave(
        CustomerResourceModel $subject,
        Customer $customer
    ) {
        if ($this->configurations->isModuleEnabled()) {
            try {
                // Validate customer fields
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
