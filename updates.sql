/* oa_network */
CREATE TABLE IF NOT EXISTS `oa_network` (
  `index_id` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `ip_address_v4` varchar(15) NOT NULL,
  `ip_subnet` varchar(15) NOT NULL,
  `net_mac_address` varchar(12) NOT NULL,
  `location_group_id` int(11) NOT NULL,
  `network_comments` varchar(100) NOT NULL,
  `proxy_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/* oa_org */
ALTER TABLE `oa_org` CHANGE `parent_id` `parent_id` INT( 10 ) NOT NULL DEFAULT '-1';
UPDATE `oa_org` SET `parent_id` = '-1' WHERE `oa_org`.`id` = 0;

/* oa_group */
// ALTER TABLE `oa_group` ADD `location_id` INT NOT NULL ;

/* oa_proxy */
// CREATE TABLE `openaudit`.`oa_proxy` ( `proxy_id` INT NOT NULL, UNIQUE (`proxy_id`))
// ALTER TABLE `oa_proxy` ADD `ip_address_v4` VARCHAR(30) NOT NULL AFTER `proxy_id`;
// ALTER TABLE `oa_proxy` ADD `proxy_name` VARCHAR(30) NOT NULL AFTER `ip_address_v4`;
// ALTER TABLE `oa_proxy` ADD `active` BOOLEAN NOT NULL ;

/* oa_location_network */
// ALTER TABLE `oa_location_network` ADD `proxy_id` INT NOT NULL ;

/* oa_location */
ALTER TABLE `oa_location` ADD `org_id` INT NOT NULL AFTER `group_id`;

/* config options */
// INSERT INTO `openaudit`.`oa_config` (`config_name`, `config_value`, `config_editable`, `config_edited_date`, `config_edited_by`, `config_description`) VALUES ('show_icon_labels', 'y', 'y', '0000-00-00 00:00:00.000000', '0', 'Show labels below the icons on view/edit pages');
