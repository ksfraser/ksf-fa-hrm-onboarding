<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Onboarding\Repository;
final class OnboardingRepository {
    private string $table = 'hrm_onboarding_checklists';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT c.*, p.name AS person_name FROM " . TB_PREF . $this->table . " c"
            . " LEFT JOIN " . TB_PREF . "crm_persons p ON c.person_id = p.id"
            . " ORDER BY c.created_at DESC, c.checklist_id DESC";
        $result = db_query($sql, 'Could not load onboarding checklists');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT c.*, p.name AS person_name FROM " . TB_PREF . $this->table . " c"
            . " LEFT JOIN " . TB_PREF . "crm_persons p ON c.person_id = p.id"
            . " WHERE c.checklist_id = " . (int) $id;
        $result = db_query($sql, 'Could not load onboarding checklist');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function listForPerson(int $personId): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT c.*, p.name AS person_name FROM " . TB_PREF . $this->table . " c"
            . " LEFT JOIN " . TB_PREF . "crm_persons p ON c.person_id = p.id"
            . " WHERE c.person_id = " . (int) $personId
            . " ORDER BY c.due_date ASC, c.checklist_id ASC";
        $result = db_query($sql, 'Could not load onboarding checklists by person');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function insertChecklist(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                person_id, checklist_type, item, completed, completed_at, notes, due_date, created_at, updated_at)
            VALUES ("
            . $this->intVal($data['person_id'] ?? 0) . ", "
            . $this->escape($data['checklist_type'] ?? '') . ", "
            . $this->escape($data['item'] ?? '') . ", "
            . $this->intVal($data['completed'] ?? 0) . ", "
            . $this->escape($data['completed_at'] ?? null) . ", "
            . $this->escape($data['notes'] ?? '') . ", "
            . $this->escape($data['due_date'] ?? null) . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ")";
        $this->dbQuery($sql);
        return $this->dbInsertId();
    }
    public function updateState(int $checklistId, string $completed, string $completedAt = null, string $notes = null): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = ["completed = " . $this->intVal($completed)];
        $sets[] = "completed_at = " . $this->escape($completedAt);
        $sets[] = "notes = " . $this->escape($notes ?? '');
        $sets[] = "updated_at = " . $this->escape(date('Y-m-d H:i:s'));
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets)
            . " WHERE checklist_id = " . $this->intVal($checklistId);
        $this->dbQuery($sql);
    }
    private function dbQuery($sql, $errorMsg = null) {
        return db_query($sql, $errorMsg);
    }
    private function dbInsertId() {
        return db_insert_id();
    }
    private function escape($value): string {
        if ($value === null) { return 'NULL'; }
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
}
