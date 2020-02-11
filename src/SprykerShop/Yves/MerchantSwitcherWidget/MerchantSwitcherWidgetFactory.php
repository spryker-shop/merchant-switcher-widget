<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget;

use ArrayObject;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\MerchantCookie;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\MerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReader;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReaderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getMerchantSearchClient(),
            $this->createMerchantCookie()
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\MerchantCookieInterface
     */
    public function createMerchantCookie(): MerchantCookieInterface
    {
        return new MerchantCookie(
            $this->getCookies(),
            $this->getRequest(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    public function getMerchantSearchClient(): MerchantSwitcherWidgetToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->getApplication()['request'];
    }

    /**
     * @return \ArrayObject|\Symfony\Component\HttpFoundation\Cookie[]
     */
    public function getCookies(): ArrayObject
    {
        return $this->getApplication()['cookies'];
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::PLUGIN_APPLICATION);
    }
}
