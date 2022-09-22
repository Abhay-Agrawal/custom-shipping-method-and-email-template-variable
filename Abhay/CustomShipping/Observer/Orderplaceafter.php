<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Abhay\CustomShipping\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
 
/**
 *  SalesOrderPlaceAfterObserver Observer.
 */
class Orderplaceafter implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Sales Order Place After event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $shippingMethod = $order->getShippingMethod();
            if ($order->getShippingMethod() == 'customshipping_customshipping') {
                $order->setCustomShipping(1)->save();
            } else {
                $order->setCustomShipping(0)->save();
            }
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
}
