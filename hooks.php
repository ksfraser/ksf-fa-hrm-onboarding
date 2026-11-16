<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\HRM\Onboarding\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_HRM_Onboarding', 192 << 8);
if (!defined('SA_ONBOARDING_VIEW')) {
    define('SA_ONBOARDING_VIEW', SS_ksf_FA_HRM_Onboarding | 1);
    define('SA_ONBOARDING_MANAGE', SS_ksf_FA_HRM_Onboarding | 2);
    define('SA_ONBOARDING_ADMIN', SS_ksf_FA_HRM_Onboarding | 3);
}
class hooks_ksf_FA_HRM_Onboarding extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'ONBOARDING_RESOLVE', 'ONBOARDING_FIELDS', 'ONBOARDING_LIST');
    var $module_name = 'ksf_FA_HRM_Onboarding';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_HRM_Onboarding] = _("Onboarding");
        $security_areas['SA_ONBOARDING_VIEW'] = array(SA_ONBOARDING_VIEW, _("View onboarding checklists"));
        $security_areas['SA_ONBOARDING_MANAGE'] = array(SA_ONBOARDING_MANAGE, _("Manage onboarding checklists"));
        $security_areas['SA_ONBOARDING_ADMIN'] = array(SA_ONBOARDING_ADMIN, _("Administer onboarding"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\HRM\Onboarding\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array(
            '0_hrm_onboarding_checklists.sql' => array('hrm_onboarding_checklists'),
        ), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\HRM\Onboarding\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array(
            'SS_ksf_FA_HRM_Onboarding' => SS_ksf_FA_HRM_Onboarding,
            'SA_ONBOARDING_VIEW' => SA_ONBOARDING_VIEW,
            'SA_ONBOARDING_MANAGE' => SA_ONBOARDING_MANAGE,
            'SA_ONBOARDING_ADMIN' => SA_ONBOARDING_ADMIN,
        );
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if ($data['type'] !== 'hrm_onboarding_checklist') { return null; }
        if (!isset($data['fields']) || !is_array($data['fields'])) { $data['fields'] = array(); }
        $data['fields'] = array_merge($data['fields'], array(
            'checklist_id' => _('Checklist ID'),
            'person_id' => _('Employee'),
            'checklist_type' => _('Checklist Type'),
            'item' => _('Item'),
            'completed' => _('Completed'),
            'due_date' => _('Due Date'),
        ));
        return null;
    }
    public function ONBOARDING_RESOLVE(&$data, $opts = null) {
        if (!isset($data['checklist_id']) || !is_numeric($data['checklist_id'])) { $data['error'] = 'ONBOARDING_RESOLVE requires data[checklist_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'ONBOARDING_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Onboarding\Service\OnboardingService();
        $data['result'] = $service->resolveById((int) $data['checklist_id'], $data['dto']);
        return null;
    }
    public function ONBOARDING_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'checklist_id' => _('Checklist ID'),
            'person_id' => _('Employee'),
            'checklist_type' => _('Checklist Type'),
            'item' => _('Item'),
            'completed' => _('Completed'),
            'due_date' => _('Due Date'),
        );
        return null;
    }
    public function ONBOARDING_LIST(&$data, $opts = null) {
        if (!isset($data['person_id']) || !is_numeric($data['person_id'])) { $data['error'] = 'ONBOARDING_LIST requires data[person_id]'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Onboarding\Service\OnboardingService();
        $data['result'] = $service->listForPerson((int) $data['person_id']);
        return null;
    }
}
