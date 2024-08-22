<?php

declare(strict_types=1);

namespace WaPoNe\InputFieldsValidator\Utility;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\Store;

/**
 * Class Notificator
 */
class Notificator
{
    private const EMAIL_SUBJECT = 'WaPoNe_InputFieldsValidator';
    private const EMAIL_TEMPLATE = 'wapone_ifv_alert';

    /**
     * @var StateInterface
     */
    private StateInterface $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    private TransportBuilder $transportBuilder;

    /**
     * @var Configurations
     */
    private Configurations $configurations;

    /**
     * Notificator constructor.
     *
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param Configurations $configurations
     */
    public function __construct(
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        Configurations $configurations
    ) {
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->configurations = $configurations;
    }

    /**
     * Send email
     *
     * @param $bodyEmail
     *
     * @return array
     */
    public function sendMail($bodyEmail): array
    {
        $this->inlineTranslation->suspend();

        try {
            // getting email to addresses
            $toAddress = array_map(
                'trim',
                explode(Constants::EMAILS_SEPARATOR, $this->configurations->getEmailTo())
            );

            foreach ($toAddress as $to) {
                $this->transportBuilder
                    ->setTemplateIdentifier(self::EMAIL_TEMPLATE)
                    ->setTemplateOptions([
                        'area' => Area::AREA_ADMINHTML,
                        'store' => Store::DEFAULT_STORE_ID,
                    ])
                    ->setTemplateVars([
                        'subject' => self::EMAIL_SUBJECT,
                        'body' => $bodyEmail
                    ])
                    ->setFromByScope([
                        'email' => $this->configurations->getEmailFrom(),
                        'name' => $this->configurations->getEmailFromName()
                    ])
                    ->addTo($to);
                $transport = $this->transportBuilder->getTransport();

                $transport->sendMessage();
            }
            $result['code'] = true;
            return $result;
        } catch (Exception $e) {
            $result['code'] = false;
            $result['msg'] = $e->getMessage();
            return $result;
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}
