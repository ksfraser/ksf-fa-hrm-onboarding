<?php
declare(strict_types=1);

use Ksfraser\FrontAccounting\HRM\Onboarding\Controller\OnboardingTabController;

$path_to_root = '../..';

require_once($path_to_root . '/includes/session.inc');

if (!function_exists('page_security')) {
    define('page_security', 'SA_ONBOARDING_VIEW');
}

$controller = new OnboardingTabController(null, array());
$controller->render();
