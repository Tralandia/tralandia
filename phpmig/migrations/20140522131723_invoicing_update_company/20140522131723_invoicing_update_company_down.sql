-- InvoicingUpdateCompany migration DOWN file

ALTER TABLE invoicing_company CHANGE locality locality_id INT DEFAULT NULL;
ALTER TABLE invoicing_company ADD CONSTRAINT FK_685A45C588823A92 FOREIGN KEY (locality_id) REFERENCES location (id);
CREATE INDEX IDX_685A45C588823A92 ON invoicing_company (locality_id);
