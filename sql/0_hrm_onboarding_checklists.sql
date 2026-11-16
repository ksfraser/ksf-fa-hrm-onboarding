-- ksf_FA_HRM_Onboarding: onboarding checklist tables

CREATE TABLE IF NOT EXISTS `0_hrm_onboarding_checklists` (
  `checklist_id`  INT(11) NOT NULL AUTO_INCREMENT,
  `person_id`     INT(11) NOT NULL COMMENT 'FK to crm_persons.id (new hire)',
  `checklist_type` VARCHAR(32) NOT NULL COMMENT 'e.g. new_hire, role_change, offboarding',
  `item`          VARCHAR(255) NOT NULL COMMENT 'checklist item description',
  `completed`     TINYINT(1) NOT NULL DEFAULT 0,
  `completed_at`  DATETIME DEFAULT NULL,
  `notes`         TEXT DEFAULT NULL,
  `due_date`      DATE DEFAULT NULL,
  `created_at`    DATETIME NOT NULL,
  `updated_at`    DATETIME NOT NULL,
  PRIMARY KEY (`checklist_id`),
  KEY `idx_person` (`person_id`),
  KEY `idx_type` (`checklist_type`),
  KEY `idx_completed` (`completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
