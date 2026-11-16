<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Onboarding\App;

use ksfraser\FrontAccounting\Common\App\AbstractFrontController;

class OnboardingFrontController extends AbstractFrontController
{
    public function __construct(
        OnboardingAppShell $appShell,
        string $fallbackSecurityArea,
        string $path_to_root
    ) {
        parent::__construct($appShell, $fallbackSecurityArea, $path_to_root);
    }
}
