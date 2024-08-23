<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Plugin;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\InputException;
use WaPoNe\InputFieldsValidator\Utility\Constants;

/**
 * Class OrderSourceLogger
 */
class OrderSourceLogger extends AbstractInputFieldsValidator
{
    /**
     * @throws InputException
     */
    public function beforeSave(
        OrderRepositoryInterface $subject,
        $order
    ) {
        if ($this->configurations->isModuleEnabled()) {
            // Order-specific logic here...
            $isApiOrder = false;

            // Check if the order is placed via API by inspecting the current request
            if (php_sapi_name() !== 'cli' && isset($_SERVER['HTTP_USER_AGENT'])) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                if (strpos($userAgent, 'REST') !== false || strpos($userAgent, 'API') !== false) {
                    $isApiOrder = true;
                }
            }

            try {
                // Validate firstname and lastname with length limit
                $this->validateInput(
                    $order->getCustomerFirstname(), 'First Name', $this->configurations->getCharactersLimit()
                );
                $this->validateInput(
                    $order->getCustomerLastname(), 'Last Name', $this->configurations->getCharactersLimit()
                );

                // Validate company and other address fields for disallowed characters
                $billingAddress = $order->getBillingAddress();
                $shippingAddress = $order->getShippingAddress();

                if ($billingAddress) {
                    $this->validateInput($billingAddress->getFirstname(), 'Billing Firstname');
                    $this->validateInput($billingAddress->getLastname(), 'Billing Lastname');
                    $this->validateInput($billingAddress->getCompany(), 'Billing Company');
                    $this->validateInput($billingAddress->getCity(), 'Billing City');
                    $this->validateInput($billingAddress->getPostcode(), 'Billing Postcode');
                    $this->validateInput($billingAddress->getStreetLine(1), 'Billing Street Address');
                    // Validate State/Region for billing address
                    $billingRegion = $billingAddress->getRegion();
                    if ($billingRegion) {
                        $this->validateInput($billingRegion->getRegion(), 'Billing State/Region Name');
                        $this->validateInput($billingRegion->getRegionCode(), 'Billing State/Region Code');
                        $this->validateInput($billingRegion->getRegionId(), 'Billing State/Region ID');
                    }
                }

                if ($shippingAddress) {
                    $this->validateInput($shippingAddress->getFirstname(), 'Shipping Firstname');
                    $this->validateInput($shippingAddress->getLastname(), 'Shipping Lastname');
                    $this->validateInput($shippingAddress->getCompany(), 'Shipping Company');
                    $this->validateInput($shippingAddress->getCity(), 'Shipping City');
                    $this->validateInput($shippingAddress->getPostcode(), 'Shipping Postcode');
                    $this->validateInput($shippingAddress->getStreetLine(1), 'Shipping Street Address');
                    // Validate State/Region for shipping address
                    $shippingRegion = $shippingAddress->getRegion();
                    if ($shippingRegion) {
                        $this->validateInput($shippingRegion->getRegion(), 'Shipping State/Region Name');
                        $this->validateInput($shippingRegion->getRegionCode(), 'Shipping State/Region Code');
                        $this->validateInput($shippingRegion->getRegionId(), 'Shipping State/Region ID');
                    }
                }

                // Assuming there is a field for order comments
                if ($order->getCustomerNote()) {
                    $this->validateInput($order->getCustomerNote(), 'Order Comments');
                }

                // Log the source of the order and additional request details
                $orderSource = $isApiOrder ? 'API' : 'Web';
                $this->logger->info('Order placed via ' . $orderSource . ': Order ID ' . $order->getEntityId());
            } catch (InputException $e) {
                // Log the unsuccessful attempt
                $this->logAndNotify($e);
                // Rethrow the exception to stop the order from being saved
                throw $e;
            }
        }
    }
}
