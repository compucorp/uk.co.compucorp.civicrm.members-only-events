-- /*******************************************************
-- *
-- * civicrm_membersonlyevent
-- *
-- * Members Only Event entity settings table
-- *
-- *******************************************************/
CREATE TABLE `civicrm_membersonlyevent` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique Members Only Event ID',
     `event_id` int unsigned NOT NULL   COMMENT 'Foreign key for the Event',
     `membership_url` varchar(255) NOT NULL   COMMENT 'URL for purchasing membership',
     `members_event_type` int unsigned   DEFAULT 0 COMMENT 'If the value is other than 1, only users with "Can register for Members events" will be able to register for this event.'
,
    PRIMARY KEY ( `id` )


,          CONSTRAINT FK_civicrm_membersonlyevent_event_id FOREIGN KEY (`event_id`) REFERENCES `civicrm_event`(`id`) ON DELETE CASCADE
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;

-- /*******************************************************
-- *
-- * civicrm_membersonlyevent_config
-- *
-- *******************************************************/
CREATE TABLE `civicrm_membersonlyevent_config` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique MembersEventConfig ID',
     `duration_check` int unsigned   DEFAULT 0 COMMENT 'Enable membership duration check'
,
	 `registration_restriction` int unsigned   DEFAULT 1 COMMENT 'First ticket purchasing restricts to current user'
,
    PRIMARY KEY ( `id` )


)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;

INSERT INTO civicrm_membersonlyevent_config (`duration_check`) VALUES (0);

-- /*******************************************************
-- *
-- * civicrm_membersonlyevent_price
-- *
-- *******************************************************/
CREATE TABLE `civicrm_membersonlyevent_price` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique MembersEventPrice ID',
     `event_id` int unsigned    COMMENT 'FK to Event',
     `price_value_id` int unsigned    COMMENT 'FK to Price Field Value',
     `is_member_price` tinyint   DEFAULT 0
,
    PRIMARY KEY ( `id` )


,          CONSTRAINT FK_civicrm_membersonlyevent_price_event_id FOREIGN KEY (`event_id`) REFERENCES `civicrm_event`(`id`) ON DELETE CASCADE,          CONSTRAINT FK_civicrm_membersonlyevent_price_price_value_id FOREIGN KEY (`price_value_id`) REFERENCES `civicrm_price_field_value`(`id`) ON DELETE CASCADE
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;