-- PsLandingPage migration DOWN file


ALTER TABLE page ADD genericContent_id INT DEFAULT NULL, CHANGE parameters parameters longtext DEFAULT NULL;
ALTER TABLE page ADD CONSTRAINT FK_140AB620F0A1919F FOREIGN KEY (genericContent_id) REFERENCES phrase (id);
CREATE UNIQUE INDEX UNIQ_140AB620F0A1919F ON page (genericContent_id);
