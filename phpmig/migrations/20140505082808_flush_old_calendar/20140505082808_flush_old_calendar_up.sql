-- FlushOldCalendar migration UP file

/* 13:21:38 _Tralandia */ ALTER TABLE `rental` ADD `oldCalendar` LONGTEXT  NULL  AFTER `calendarUpdated`;
/* 13:22:17 _Tralandia */ ALTER TABLE `rental` ADD `oldCalendarUpdated` DATETIME  NULL  AFTER `oldCalendar`;
/* 13:22:32 _Tralandia */ ALTER TABLE `rental` ADD INDEX (`oldCalendarUpdated`);


update rental set oldCalendar = calendar, oldCalendarUpdated = calendarUpdated;
update rental set calendar = NULL, calendarUpdated = NULL;
