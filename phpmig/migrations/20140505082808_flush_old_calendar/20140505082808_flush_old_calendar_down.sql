-- FlushOldCalendar migration DOWN file


update rental set calendar = oldCalendar, calendarUpdated = oldCalendarUpdated;

/* 13:22:57 _Tralandia */ ALTER TABLE `rental` DROP INDEX `oldCalendarUpdated`;
/* 13:23:07 _Tralandia */ ALTER TABLE `rental` DROP `oldCalendarUpdated`;
/* 13:23:13 _Tralandia */ ALTER TABLE `rental` DROP `oldCalendar`;
