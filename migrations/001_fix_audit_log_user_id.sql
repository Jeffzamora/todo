-- Fix: make audit_log.user_id compatible with usuarios.id (BIGINT UNSIGNED)
-- Run this AFTER importing todo_pro.sql (or apply directly in your DB)

ALTER TABLE audit_log
  MODIFY user_id BIGINT(20) UNSIGNED NULL;
