<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Onboarding\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\Menu\FAModuleMenu;
use Ksfraser\FrontAccounting\HRM\Onboarding\Controller\OnboardingTabController;

class OnboardingAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('onboarding', 'index.php', 'view', 'onboarding_checklists');

        $this->add_module(_("Onboarding"));

        $menu = new FAModuleMenu(
            'modules/ksf_FA_HRM_Onboarding/index.php',
            'view',
            ''
        );

        $menu->addItem('onboarding_checklists', _("Onboarding Checklists"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ONBOARDING_VIEW');

        $this->add_extensions();
    }
}
