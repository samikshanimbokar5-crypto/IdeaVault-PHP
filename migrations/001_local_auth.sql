USE railway;

ALTER TABLE users
    MODIFY google_id VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255) NULL AFTER avatar_url;