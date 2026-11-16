<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Onboarding\Service;
use Ksfraser\FrontAccounting\HRM\Onboarding\DTO\OnboardingDTO;
use Ksfraser\FrontAccounting\HRM\Onboarding\Repository\OnboardingRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class OnboardingService {
    private $repo;
    public function __construct(?OnboardingRepository $repo = null) { $this->repo = $repo ?? new OnboardingRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('hrm_onboarding_checklist', '0', 'hrm_onboarding_checklist', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Onboarding Checklist #') . (string) $id);
        }
        $r = OnboardingDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('hrm_onboarding_checklist', (string) $r->checklist_id, 'hrm_onboarding_checklist', (string) $r->checklist_id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'checklist_id' => (string) $r->checklist_id,
                'person_id' => $r->person_name,
                'checklist_type' => $r->checklist_type,
                'item' => $r->item,
                'completed' => $r->completed,
                'due_date' => $r->due_date,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_HRM_Onboarding/pages/onboarding_checklist_form.php?action=edit&checklist_id=' . $r->checklist_id,
            'label' => $r->getDisplayLabel(),
        );
    }
    public function listForPerson(int $personId): array {
        $rows = $this->repo->listForPerson($personId);
        $results = array();
        foreach ($rows as $row) {
            $r = OnboardingDTO::fromArray($row);
            $results[] = array(
                'id' => $r->checklist_id,
                'dto' => new RelatedRecordDto('hrm_onboarding_checklist', (string) $r->checklist_id, 'hrm_onboarding_checklist', (string) $r->checklist_id, null),
                'fields' => array(
                    'checklist_id' => (string) $r->checklist_id,
                    'person_id' => $r->person_name,
                    'checklist_type' => $r->checklist_type,
                    'item' => $r->item,
                    'completed' => $r->completed,
                    'due_date' => $r->due_date,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRM_Onboarding/pages/onboarding_checklist_form.php?action=edit&checklist_id=' . $r->checklist_id,
                'label' => $r->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $r = OnboardingDTO::fromArray($row);
            $results[] = array(
                'id' => $r->checklist_id,
                'fields' => array(
                    'checklist_id' => (string) $r->checklist_id,
                    'person_name' => $r->person_name,
                    'checklist_type' => $r->checklist_type,
                    'item' => $r->item,
                    'completed' => $r->completed,
                    'due_date' => $r->due_date,
                    'url' => '/modules/ksf_FA_HRM_Onboarding/pages/onboarding_checklist_form.php?action=edit&checklist_id=' . $r->checklist_id,
                ),
            );
        }
        return $results;
    }
    public function insertChecklist(array $data): int {
        return $this->repo->insertChecklist($data);
    }
    public function updateState(int $checklistId, string $completed, string $completedAt = null, string $notes = null): void {
        $this->repo->updateState($checklistId, $completed, $completedAt, $notes);
        onboarding_checklist_state_changed((string) $checklistId, '', $completed, array('notes' => $notes));
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
            'checklist_id' => _('Checklist ID'),
            'person_id' => _('Employee'),
            'checklist_type' => _('Checklist Type'),
            'item' => _('Item'),
            'completed' => _('Completed'),
            'due_date' => _('Due Date'),
        );
    }
    public function getStateTransitions(string $currentState): array {
        $transitions = array(
            '0' => array('1' => _('Mark Complete')),
            '1' => array('0' => _('Reopen')),
        );
        return isset($transitions[$currentState]) ? $transitions[$currentState] : array();
    }
    public function isAllowedTransition(string $fromState, string $toState): bool {
        $transitions = $this->getStateTransitions($fromState);
        return isset($transitions[$toState]);
    }
}
