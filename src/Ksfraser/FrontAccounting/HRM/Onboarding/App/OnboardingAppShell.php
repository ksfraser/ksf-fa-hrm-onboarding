<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Onboarding\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\App\TabRegistration;

class OnboardingAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('onboarding', 'index.php', 'view', 'onboarding_checklists');

        $this->registerTab(new TabRegistration(
            'onboarding_checklists',
            'Onboarding Checklists',
            'SA_ONBOARDING_VIEW',
            null,
            10,
            0,
            null,
            null,
            array()
        ));
    }
}
