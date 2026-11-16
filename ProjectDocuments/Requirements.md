# ksf_FA_HRM_Onboarding — Requirements

## Purpose
Manage new-hire onboarding checklists: task items, completion tracking, due dates and sign-off per employee.

## Business Rules (BR-HRM-06)
- An onboarding checklist item belongs to one employee and one checklist type.
- Each item tracks completion (boolean), completion timestamp, notes and due date.
- Completion state transitions: incomplete → complete → (optional) reopen.
- State changes fire the ONBOARDING_STATE_CHANGED hook.

## Functional Requirements
- FR-ON-01: Create an onboarding checklist item for an employee.
- FR-ON-02: List all onboarding checklist items (summary table).
- FR-ON-03: Resolve a single checklist item by id (related-record linking).
- FR-ON-04: Mark an item complete/reopen with timestamp and notes.
- FR-ON-05: List checklist items for a given employee.
- FR-ON-06: Expose onboarding fields via RELATED_TO_FIELDS.

## Non-Functional Requirements
- NF-ON-01: All database access uses native FA db_* calls.
- NF-ON-02: PHP 7.3 compatible runtime.
- NF-ON-03: Unit tests cover DTO round-trip and label resolution.
