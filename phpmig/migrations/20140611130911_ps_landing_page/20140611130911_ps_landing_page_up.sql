-- PsLandingPage migration UP file



ALTER TABLE page DROP FOREIGN KEY FK_140AB620F0A1919F;
DROP INDEX UNIQ_140AB620F0A1919F ON page;
ALTER TABLE page DROP genericContent_id, CHANGE parameters parameters longtext DEFAULT NULL;
