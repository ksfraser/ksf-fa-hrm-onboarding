# ksf_FA_HRM_Onboarding — Requirements Traceability Matrix

| Req ID | Requirement | Source | Implemented In | Verified By |
|--------|-------------|--------|----------------|-------------|
| BR-HRM-06 | Onboarding/offboarding checklists | HR-CRM_gaps.md H6 | OnboardingService | unit tests |
| FR-ON-01 | Create checklist item | BR-HRM-06 | OnboardingRepository::insertChecklist | OnboardingDtoTest |
| FR-ON-02 | List checklist items | BR-HRM-06 | OnboardingService::listAll | (tab controller) |
| FR-ON-03 | Resolve item by id | BR-HRM-06 | OnboardingService::resolveById | (capability hook) |
| FR-ON-04 | Mark complete/reopen | BR-HRM-06 | OnboardingService::updateState | (state transitions) |
| FR-ON-05 | List for employee | BR-HRM-06 | OnboardingRepository::listForPerson | (service) |
| FR-ON-06 | RELATED_TO_FIELDS | BR-HRM-06 | hooks.php RELATED_TO_FIELDS | (capability) |
| NF-ON-01 | Native db_* calls | AGENTS_ARCH | repository | code review |
| NF-ON-02 | PHP 7.3 compatible | AGENTS_ARCH | all src | code review |
| NF-ON-03 | Unit tests | AGENTS_ARCH | tests/Unit | phpunit |
