-- UserAddInvoicingInformation migration UP file


ALTER TABLE user ADD invoicingInformation longtext DEFAULT NULL;
