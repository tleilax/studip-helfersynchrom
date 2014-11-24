<?php
class AdjustHelpTables extends DBMigration
{

    public function description ()
    {
        return 'Transforms the help_content table';
    }

    public function up ()
    {
        $query = "ALTER TABLE `help_content`
                      DROP PRIMARY KEY,
                      DROP COLUMN `label`,
                      DROP COLUMN `icon`,
                      DROP COLUMN `position`,
                      DROP COLUMN `custom`,
                      MODIFY COLUMN `installation_id` VARCHAR(32) NOT NULL,
                      MODIFY COLUMN `visible` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
                      CHANGE COLUMN `author_id` `author_email` VARCHAR(128) NOT NULL,
                      ADD COLUMN `chdate` INT(11) UNSIGNED NOT NULL,
                      ADD PRIMARY KEY (`route`, `studip_version`, `language`, `installation_id`)";
        DBManager::get()->exec($query);

        $query = "ALTER TABLE `help_tours`
                     DROP PRIMARY KEY,
                     DROP COLUMN `version`,
                     ADD COLUMN `tour_global_id` CHAR(32) NOT NULL FIRST,
                     ADD COLUMN `chdate` INT (11) UNSIGNED NOT NULL,
                     ADD COLUMN `author_email` VARCHAR(128) NOT NULL,
                     ADD PRIMARY KEY (`route_id`, `studip_version`, `language`, `installation_id`)";
        DBManager::get()->exec($query);

        $query = "ALTER TABLE `help_tour_steps`
                     ADD COLUMN `chdate` INT(11) UNSIGNED NOT NULL,
                     MODIFY COLUMN `interactive` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
                     CHANGE COLUMN `author_id` VARCHAR(128) NOT NULL";
        DBManager::get()->exec($query);

        $query = "UPDATE `help_content` SET `chdate` = `mkdate`";
        DBManager::get()->exec($query);

        $query = "UPDATE `help_tours` SET `chdate` = `mkdate`";
        DBManager::get()->exec($query);

        $query = "UPDATE `help_tour_steps` SET `chdate` = `mkdate`";
        DBManager::get()->exec($query);
    }

    public function down ()
    {
        $query = "ALTER TABLE `help_content`
                      DROP PRIMARY KEY,
                      ADD COLUMN `label` VARCHAR(255) NOT NULL,
                      ADD COLUMN `icon` VARCHAR(255) NOT NULL,
                      ADD COLUMN `position` TINYINT(4) NOT NULL DEFAULT 1,
                      ADD COLUMN `custom` TINYINT(4) NOT NULL DEFAULT 0,
                      MODIFY COLUMN `installation_id` VARCHAR(255) NOT NULL,
                      CHANGE COLUMN `author_email` `author_id` CHAR(32) NOT NULL,
                      DROP COLUMN `chdate`,
                      ADD PRIMARY KEY (`route`, `studip_version`, `language`, `position`, `custom`)";
        DBManager::get()->exec($query);

        $query = "ALTER TABLE `help_tours`
                     DROP PRIMARY KEY,
                     ADD COLUMN `version` INT(11) UNISGNED NOT NULL,
                     DROP COLUMN `tour_global_id`,
                     DROP COLUMN `chdate`,
                     DROP COLUMN `author_email`,
                     ADD PRIMARY KEY (`tour_id`)";
        DBManager::get()->exec($query);

        $query = "ALTER TABLE `help_tour_steps`
                     DROP COLUMN `chdate`,
                     MODIFY COLUMN `interactive` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
                     CHANGE COLUMN `author_email` `author_id` CHAR(32) NOT NULL";
        DBManager::get()->exec($query);
    }
}
