<?php
declare(strict_types=1);

if (!function_exists('hook_invoke_all')) {
    return;
}

function onboarding_checklist_state_changed($checklistId, $fromState, $toState, $meta = array()) {
    hook_invoke_all('ONBOARDING_STATE_CHANGED', array(
        'checklist_id' => $checklistId,
        'from_state' => $fromState,
        'to_state' => $toState,
        'meta' => $meta,
    ));
}
