-- EmailLayoutIsNewsletter migration UP file


ALTER TABLE email_template ADD isNewsletter TINYINT(1) NOT NULL;
