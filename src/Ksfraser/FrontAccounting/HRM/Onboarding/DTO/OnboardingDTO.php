<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Onboarding\DTO;
final class OnboardingDTO {
    public $checklist_id;
    public $person_id;
    public $person_name;
    public $checklist_type;
    public $item;
    public $completed;
    public $completed_at;
    public $notes;
    public $due_date;
    public $created_at;
    public $updated_at;
    public function __construct(
        int $checklist_id,
        int $person_id = 0,
        string $person_name = '',
        string $checklist_type = '',
        string $item = '',
        string $completed = '0',
        string $completed_at = '',
        string $notes = '',
        string $due_date = '',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->checklist_id = $checklist_id;
        $this->person_id = $person_id;
        $this->person_name = $person_name;
        $this->checklist_type = $checklist_type;
        $this->item = $item;
        $this->completed = $completed;
        $this->completed_at = $completed_at;
        $this->notes = $notes;
        $this->due_date = $due_date;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function getDisplayLabel(): string {
        if ($this->person_name !== '' && $this->item !== '') {
            return sprintf(_('%s — %s for %s'), $this->checklist_type, $this->item, $this->person_name);
        }
        return _('Onboarding Checklist #') . (string) $this->checklist_id;
    }
    public function toArray(): array {
        return array(
            'checklist_id' => $this->checklist_id,
            'person_id' => $this->person_id,
            'person_name' => $this->person_name,
            'checklist_type' => $this->checklist_type,
            'item' => $this->item,
            'completed' => $this->completed,
            'completed_at' => $this->completed_at,
            'notes' => $this->notes,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['checklist_id'] ?? 0),
            (int) ($data['person_id'] ?? 0),
            (string) ($data['person_name'] ?? ''),
            (string) ($data['checklist_type'] ?? ''),
            (string) ($data['item'] ?? ''),
            (string) ($data['completed'] ?? '0'),
            (string) ($data['completed_at'] ?? ''),
            (string) ($data['notes'] ?? ''),
            (string) ($data['due_date'] ?? ''),
            (string) ($data['created_at'] ?? ''),
            (string) ($data['updated_at'] ?? '')
        );
    }
}
