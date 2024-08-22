<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Plugin;

use Exception;
use Magento\Framework\Exception\InputException;
use WaPoNe\InputFieldsValidator\Utility\Configurations;
use WaPoNe\InputFieldsValidator\Utility\Notificator;
use WaPoNe\InputFieldsValidator\Logger\CustomLogger;

/**
 * Abstract Class Importer
 */
abstract class AbstractInputFieldsValidator
{
    /**
     * @var Configurations
     */
    protected Configurations $configurations;

    /**
     * @var Notificator
     */
    protected Notificator $notificator;

    /**
     * @var CustomLogger
     */
    protected CustomLogger $logger;

    /**
     * AbstractImporter constructor.
     *
     * @param Configurations $configurations
     * @param Notificator $notificator
     * @param CustomLogger $logger
     */
    public function __construct(
        Configurations $configurations,
        Notificator $notificator,
        CustomLogger $logger
    ) {
        $this->configurations = $configurations;
        $this->notificator = $notificator;
        $this->logger = $logger;
    }

    /**
     * @throws InputException
     */
    protected function validateInput($input, $fieldName, $maxLength = null): void
    {
        if (empty($input)) {
            return;
        }

        // Disallowed characters
        if (preg_match($this->configurations->getRegEx(), $input)) {
            throw new InputException(__("Invalid characters in $fieldName."));
        }

        if ($maxLength !== null && strlen($input) > $maxLength) {
            throw new InputException(__("$fieldName cannot exceed $maxLength characters."));
        }
    }

    /**
     * @param InputException $e
     *
     * @return void
     */
    protected function logAndNotify(InputException $e): void
    {
        $this->logger->warning('Unsuccessful attempt: ' . $e->getMessage(), [
            'IP' => $this->_getClientIp(),
            'User Agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown User Agent',
            'Request URI' => $_SERVER['REQUEST_URI'] ?? 'Unknown URI',
        ]);

        try {
            $this->sendFailureNotification($e);
        } catch (Exception $emailException) {
            $this->logger->error('Failed to send notification email: ' . $emailException->getMessage());
        }
    }

    /**
     * @param InputException $exception
     *
     * @return void
     * @throws Exception
     */
    protected function sendFailureNotification(InputException $exception): void
    {
        $message = "Unsuccessful attempt:\n";
        $message .= "Error Message: " . $exception->getMessage() . "\n";
        $message .= "IP: " . $this->_getClientIp() . "\n";
        $message .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown User Agent') . "\n";
        $message .= "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown URI') . "\n";

        // Execute a shell command to send an email
        if ($this->configurations->isNotificationsEnabled()) {
            $emailResult = $this->notificator->sendMail($message);
            if (!$emailResult['code']) {
                throw new Exception("Failed to send email: " . $emailResult['msg']);
            }
        }
    }

    /**
     * Get Client IP
     */
    private function _getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
