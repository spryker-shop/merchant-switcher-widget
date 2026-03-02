<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Cookie;

interface SelectedMerchantCookieInterface
{
    public function getMerchantReference(): string;

    public function setMerchantReference(?string $selectedMerchantReference): void;

    public function removeMerchantReference(): void;
}
