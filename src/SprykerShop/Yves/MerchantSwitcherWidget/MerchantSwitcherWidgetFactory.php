<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget;

use ArrayObject;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookie;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Form\MerchantSwitcherSelectorForm;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReader;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReaderInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcher;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherWidgetFactory extends AbstractFactory
{
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getMerchantSearchClient(),
            $this->createSelectedMerchantCookie(),
            $this->createMerchantSwitcher(),
        );
    }

    public function createMerchantSwitcher(): MerchantSwitcherInterface
    {
        return new MerchantSwitcher(
            $this->getQuoteClient(),
            $this->getMerchantSwitcherClient(),
        );
    }

    public function createSelectedMerchantCookie(): SelectedMerchantCookieInterface
    {
        return new SelectedMerchantCookie(
            $this->getCookies(),
            $this->getRequestStack(),
            $this->getConfig(),
        );
    }

    public function getMerchantSearchClient(): MerchantSwitcherWidgetToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }

    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \ArrayObject<int, \Symfony\Component\HttpFoundation\Cookie>
     */
    public function getCookies(): ArrayObject
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::SERVICE_COOKIES);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    public function getQuoteClient(): MerchantSwitcherWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_QUOTE);
    }

    public function getMerchantSwitcherClient(): MerchantSwitcherWidgetToMerchantSwitcherClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_MERCHANT_SWITCHER);
    }

    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::FORM_FACTORY);
    }

    public function getMerchantSwitcherSelectorForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantSwitcherSelectorForm::class);
    }
}
