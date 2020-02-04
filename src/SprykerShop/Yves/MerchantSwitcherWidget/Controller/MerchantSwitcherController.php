<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 */
class MerchantSwitcherController extends AbstractController
{
    protected const PARAM_MERCHANT_REFERENCE = 'merchant-reference';
    protected const HEADER_REFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchMerchantAction(Request $request): RedirectResponse
    {
        $merchantReference = $request->get(static::PARAM_MERCHANT_REFERENCE);

        $cookie = Cookie::create(
            $this->getFactory()->getConfig()->getMerchantSelectorCookieTimeExpiration(),
            $merchantReference,
            time() + $this->getFactory()->getConfig()->getCookieTimeExpiration()
        );

        $response = $this->createRedirectResponse($request);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request): RedirectResponse
    {
        return $this->redirectResponseExternal($request->headers->get(static::HEADER_REFERER));
    }
}
