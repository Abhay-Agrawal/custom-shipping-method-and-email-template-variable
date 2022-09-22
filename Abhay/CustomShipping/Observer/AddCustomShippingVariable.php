<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Abhay\CustomShipping\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 *  EmailOrderSetTemplateVarsBeforeObserver Observer.
 */
class AddCustomShippingVariable implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CountryFactory
     */
    protected $countryFactory;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param CountryFactory $countryFactory
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        CountryFactory $countryFactory,
        RegionFactory $regionFactory
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->countryFactory = $countryFactory;
        $this->regionFactory = $regionFactory;
    }

    /**
     * Email Order Set Template Vars Before event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $transportObject = $observer->getEvent()->getData('transportObject');
            $order = $transportObject->getData('order');
            $regionName = $this->getStoreRegion();
            $countryName = $this->getStoreCountry();
            $orderData = $transportObject->getData('order_data');
            $orderData['custom_shipping'] = $order->getCustomShipping();
            $orderData['region_name'] = $this->getStoreRegion();
            $orderData['country_name'] = $this->getStoreCountry();
            $transportObject->setData('order_data', $orderData);
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    /**
     * Get region name
     * @return string
     */
    public function getStoreRegion()
    {
        $regionId = $this->scopeConfig->getValue(
            'general/store_information/region_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $region = $this->regionFactory->create()->load($regionId);
        return $region->getName();
    }

    /**
     * Get country name
     * @return string
     */
    public function getStoreCountry()
    {
        $countryCode = $this->scopeConfig->getValue(
            'general/store_information/country_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }
}
