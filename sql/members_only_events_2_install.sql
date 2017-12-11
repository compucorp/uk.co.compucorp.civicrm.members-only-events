-- /*******************************************************
-- *
-- * membersonlyevent_event_membership_type
-- *
-- * Joining table for members-only-event and membership types
-- *
-- *******************************************************/
CREATE TABLE `membersonlyevent_event_membership_type` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique members-only-event and membership type association id',
     `event_id` int unsigned NOT NULL   COMMENT 'Event ID.',
     `membership_type_id` int unsigned NOT NULL   COMMENT 'Membership Type ID.'
,
        PRIMARY KEY (`id`)

    ,     INDEX `index_event_id_membership_type_id`(
        event_id
      , membership_type_id
  )

,          CONSTRAINT FK_membersonlyevent_event_membership_type_event_id FOREIGN KEY (`event_id`) REFERENCES `civicrm_event`(`id`) ON DELETE CASCADE,          CONSTRAINT FK_membersonlyevent_event_membership_type_membership_type_id FOREIGN KEY (`membership_type_id`) REFERENCES `civicrm_membership_type`(`id`) ON DELETE CASCADE
)  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;
