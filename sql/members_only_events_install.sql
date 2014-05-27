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
     `contribution_page_id` int unsigned    COMMENT 'Foreign key for the Contribution page',
     `members_event_type` tinyint   DEFAULT 1 COMMENT 'If the value is other than 1, only users with "Can register for Members events" will be able to register for this event.' 
,
    PRIMARY KEY ( `id` )
 
 
,          CONSTRAINT FK_civicrm_membersonlyevent_event_id FOREIGN KEY (`event_id`) REFERENCES `civicrm_event`(`id`) ON DELETE CASCADE,          CONSTRAINT FK_civicrm_membersonlyevent_contribution_page_id FOREIGN KEY (`contribution_page_id`) REFERENCES `civicrm_contribution_page`(`id`) ON DELETE SET NULL  
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;

CREATE TABLE `civicrm_membersonlyevent_config` (


     `id` int unsigned NOT NULL AUTO_INCREMENT,
     `duration_check` tinyint   DEFAULT 0  COMMENT 'Enable membership duration check?',
     
    PRIMARY KEY ( `id` )
  
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;

INSERT INTO civicrm_membersonlyevent_config (`duration_check`) VALUES (0);