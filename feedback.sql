CREATE TABLE IF NOT EXISTS guest_feedback (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,

  -- Placeholder: this should match the guest booking/request primary key later
  application_id BIGINT NOT NULL,

  rating_overall TINYINT NOT NULL,
  rating_cleanliness TINYINT NULL,
  rating_staff TINYINT NULL,

  comments TEXT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  UNIQUE KEY uq_feedback_application (application_id),
  INDEX idx_feedback_created (created_at),

  -- optional sanity checks (MySQL 8+ enforces CHECK)
  CHECK (rating_overall BETWEEN 1 AND 5),
  CHECK (rating_cleanliness IS NULL OR rating_cleanliness BETWEEN 1 AND 5),
  CHECK (rating_staff IS NULL OR rating_staff BETWEEN 1 AND 5)
);
