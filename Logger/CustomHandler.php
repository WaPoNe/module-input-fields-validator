<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Logger;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger as MonologLogger;

/**
 * Class CustomHandler
 */
class CustomHandler extends BaseHandler
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = MonologLogger::DEBUG;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/wapone_input_fields_validator.log';
}
