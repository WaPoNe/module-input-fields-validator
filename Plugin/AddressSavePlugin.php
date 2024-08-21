<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Plugin;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Exception\InputException;

/**
 * Class AddressSavePlugin
 */
class AddressSavePlugin extends AbstractInputFieldsValidator
{
    /**
     * @param AddressRepositoryInterface $subject
     * @param AddressInterface $address
     *
     * @return void
     * @throws InputException
     */
    public function beforeSave(
        AddressRepositoryInterface $subject,
        AddressInterface $address
    ) {
        if ($this->configurations->isModuleEnabled()) {
            try {
                // Validate address fields
                $this->validateInput($address->getCompany(), 'Company');
                $this->validateInput($address->getCity(), 'City');
                $this->validateInput($address->getPostcode(), 'Postcode');
                $this->validateInput($address->getFirstname(), 'First Name');
                $this->validateInput($address->getLastname(), 'Last Name');
                $this->validateInput($address->getTelephone(), 'Phone Number');
                // Validate State/Region
                $region = $address->getRegion();
                if ($region) {
                    $this->validateInput($region->getRegion(), 'State/Region Name');
                    $this->validateInput($region->getRegionCode(), 'State/Region Code');
                    $this->validateInput($region->getRegionId(), 'State/Region ID');
                }
                // Validate each street address line
                $streetLines = $address->getStreet();
                foreach ($streetLines as $key => $streetLine) {
                    $this->validateInput($streetLine, 'Street Address Line ' . ($key + 1));
                }
            } catch (InputException $e) {
                // Log the unsuccessful attempt
                $this->logAndNotify($e);
                // Rethrow the exception to stop the process
                throw $e;
            }
        }
    }
}
