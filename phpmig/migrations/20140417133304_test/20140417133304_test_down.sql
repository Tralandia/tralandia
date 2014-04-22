-- Test migration DOWN file

/* 08:26:36 _Tralandia */ SELECT * FROM `information_schema`.`character_sets` ORDER BY `character_set_name` ASC;
/* 08:26:36 _Tralandia */ SELECT * FROM `information_schema`.`collations` WHERE character_set_name = 'utf8' ORDER BY `collation_name` ASC;
