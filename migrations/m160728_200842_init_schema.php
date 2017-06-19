<?php

use yii\db\Migration;

class m160728_200842_init_schema extends Migration
{
    public function up()
    {
        $this->execute("
        -- -----------------------------------------------------
        -- Table `user`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `user` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `username` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
          `email` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
          `password_hash` VARCHAR(60) CHARACTER SET 'utf8' NOT NULL,
          `auth_key` VARCHAR(32) CHARACTER SET 'utf8' NOT NULL,
          `confirmed_at` INT(11) NULL DEFAULT NULL,
          `unconfirmed_email` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `blocked_at` INT(11) NULL DEFAULT NULL,
          `registration_ip` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `created_at` INT(11) NOT NULL,
          `updated_at` INT(11) NOT NULL,
          `flags` INT(11) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          UNIQUE INDEX `user_unique_email` (`email` ASC),
          UNIQUE INDEX `user_unique_username` (`username` ASC))
        ENGINE = InnoDB
        AUTO_INCREMENT = 7
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `course`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `course` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` TEXT NULL DEFAULT NULL,
          `description` TEXT NULL DEFAULT NULL,
          `position` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `course_subscription`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `course_subscription` (
          `user_id` INT(11) NOT NULL,
          `course_id` INT NOT NULL,
          `course_subscriptioncol` VARCHAR(45) NULL,
          PRIMARY KEY (`course_id`, `user_id`),
          CONSTRAINT `fk_user_has_course_user1`
            FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_user_has_course_course1`
            FOREIGN KEY (`course_id`)
            REFERENCES `course` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `challenge_type`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `challenge_type` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` TEXT NULL DEFAULT NULL,
          `description` TEXT NULL DEFAULT NULL,
          `position` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `element`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `element` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` TEXT NULL DEFAULT NULL,
          `description` TEXT NULL DEFAULT NULL,
          `position` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `subject`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `subject` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `course_id` INT(11) NOT NULL,
          `name` TEXT NULL,
          `description` TEXT NULL,
          `position` INT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_subject_course1_idx` (`course_id` ASC),
          CONSTRAINT `fk_subject_course1`
            FOREIGN KEY (`course_id`)
            REFERENCES `course` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB;


        -- -----------------------------------------------------
        -- Table `challenge`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `challenge` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `course_id` INT(11) NOT NULL,
          `challenge_type_id` INT(11) NOT NULL,
          `element_id` INT(11) NOT NULL,
          `subject_id` INT NOT NULL,
          `grade_number` INT(11) NULL DEFAULT NULL,
          `name` TEXT NULL DEFAULT NULL,
          `description` TEXT NULL DEFAULT NULL,
          `exercise_number` INT(11) NULL DEFAULT NULL,
          `exercise_challenge_number` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_challenge_course_idx` (`course_id` ASC),
          INDEX `fk_challenge_challenge_type1_idx` (`challenge_type_id` ASC),
          INDEX `fk_challenge_challenge_element1_idx` (`element_id` ASC),
          INDEX `fk_challenge_challenge_subject1_idx` (`subject_id` ASC),
          CONSTRAINT `fk_challenge_course`
            FOREIGN KEY (`course_id`)
            REFERENCES `course` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_challenge_challenge_type1`
            FOREIGN KEY (`challenge_type_id`)
            REFERENCES `challenge_type` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_challenge_challenge_element1`
            FOREIGN KEY (`element_id`)
            REFERENCES `element` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_challenge_challenge_subject1`
            FOREIGN KEY (`subject_id`)
            REFERENCES `subject` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `attempt`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `attempt` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `user_id` INT(11) NOT NULL,
          `challenge_id` INT(11) NOT NULL,
          `start_time` DATETIME NULL DEFAULT NULL,
          `finish_time` DATETIME NULL DEFAULT NULL,
          `mark` VARCHAR(32) NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_challenge_attempt_user1_idx` (`user_id` ASC),
          INDEX `fk_challenge_attempt_challenge1_idx` (`challenge_id` ASC),
          CONSTRAINT `fk_challenge_attempt_user1`
            FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_challenge_attempt_challenge1`
            FOREIGN KEY (`challenge_id`)
            REFERENCES `challenge` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `question_type`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `question_type` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(255) NULL DEFAULT NULL,
          `sysname` VARCHAR(32) NULL DEFAULT NULL,
          PRIMARY KEY (`id`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `question`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `question` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `question_type_id` INT(11) NOT NULL,
          `text` TEXT NULL DEFAULT NULL,
          `data` TEXT NULL DEFAULT NULL,
          `hint` TEXT NULL DEFAULT NULL,
          `comment` TEXT NULL DEFAULT NULL,
          `cost` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_question_question_type1_idx` (`question_type_id` ASC),
          CONSTRAINT `fk_question_question_type1`
            FOREIGN KEY (`question_type_id`)
            REFERENCES `question_type` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `answer`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `answer` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `attempt_id` INT(11) NOT NULL,
          `question_id` INT(11) NOT NULL,
          `data` TEXT NULL DEFAULT NULL,
          `correct` TINYINT(1) NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_attempt_question_attempt1_idx` (`attempt_id` ASC),
          INDEX `fk_attempt_question_question1_idx` (`question_id` ASC),
          CONSTRAINT `fk_attempt_question_attempt1`
            FOREIGN KEY (`attempt_id`)
            REFERENCES `attempt` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_attempt_question_question1`
            FOREIGN KEY (`question_id`)
            REFERENCES `question` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `auth_rule`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `auth_rule` (
          `name` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
          `data` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `created_at` INT(11) NULL DEFAULT NULL,
          `updated_at` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`name`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `auth_item`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `auth_item` (
          `name` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
          `type` INT(11) NOT NULL,
          `description` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `rule_name` VARCHAR(64) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `data` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `created_at` INT(11) NULL DEFAULT NULL,
          `updated_at` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`name`),
          INDEX `rule_name` (`rule_name` ASC),
          INDEX `idx-auth_item-type` (`type` ASC),
          CONSTRAINT `auth_item_ibfk_1`
            FOREIGN KEY (`rule_name`)
            REFERENCES `auth_rule` (`name`)
            ON DELETE SET NULL
            ON UPDATE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `auth_assignment`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `auth_assignment` (
          `item_name` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
          `user_id` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
          `created_at` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`item_name`, `user_id`),
          CONSTRAINT `auth_assignment_ibfk_1`
            FOREIGN KEY (`item_name`)
            REFERENCES `auth_item` (`name`)
            ON DELETE CASCADE
            ON UPDATE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `auth_item_child`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `auth_item_child` (
          `parent` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
          `child` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
          PRIMARY KEY (`parent`, `child`),
          INDEX `child` (`child` ASC),
          CONSTRAINT `auth_item_child_ibfk_1`
            FOREIGN KEY (`parent`)
            REFERENCES `auth_item` (`name`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
          CONSTRAINT `auth_item_child_ibfk_2`
            FOREIGN KEY (`child`)
            REFERENCES `auth_item` (`name`)
            ON DELETE CASCADE
            ON UPDATE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `challenge_generation`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `challenge_generation` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `challenge_id` INT(11) NOT NULL,
          `question_type_id` INT(11) NOT NULL,
          `question_count` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_challenge_generation_challenge1_idx` (`challenge_id` ASC),
          INDEX `fk_challenge_generation_question_type1_idx` (`question_type_id` ASC),
          CONSTRAINT `fk_challenge_generation_challenge1`
            FOREIGN KEY (`challenge_id`)
            REFERENCES `challenge` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_challenge_generation_question_type1`
            FOREIGN KEY (`question_type_id`)
            REFERENCES `question_type` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `challenge_mark`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `challenge_mark` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `challenge_id` INT(11) NOT NULL,
          `value_from` INT(11) NULL DEFAULT NULL,
          `value_to` INT(11) NULL DEFAULT NULL,
          `mark` VARCHAR(64) NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_challenge_mark_challenge1_idx` (`challenge_id` ASC),
          CONSTRAINT `fk_challenge_mark_challenge1`
            FOREIGN KEY (`challenge_id`)
            REFERENCES `challenge` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `challenge_settings`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `challenge_settings` (
          `challenge_id` INT(11) NOT NULL,
          `immediate_result` TINYINT(1) NULL DEFAULT NULL,
          `retries_enabled` TINYINT(1) NULL DEFAULT NULL,
          `registration_required` TINYINT(1) NULL DEFAULT NULL,
          `subscription_required` TINYINT(1) NULL DEFAULT NULL,
          `start_time` DATETIME NULL DEFAULT NULL,
          `finish_time` DATETIME NULL DEFAULT NULL,
          `limit_time` INT(11) NULL DEFAULT NULL,
          `limit_stop` TINYINT(1) NULL DEFAULT NULL,
          `autostart` TINYINT(1) NULL DEFAULT NULL,
          PRIMARY KEY (`challenge_id`),
          CONSTRAINT `fk_challenge_settings_challenge1`
            FOREIGN KEY (`challenge_id`)
            REFERENCES `challenge` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `migration`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `migration` (
          `version` VARCHAR(180) NOT NULL,
          `apply_time` INT(11) NULL DEFAULT NULL,
          PRIMARY KEY (`version`))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `profile`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `profile` (
          `user_id` INT(11) NOT NULL,
          `name` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `public_email` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `gravatar_email` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `gravatar_id` VARCHAR(32) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `location` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `website` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `bio` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
          PRIMARY KEY (`user_id`),
          CONSTRAINT `fk_user_profile`
            FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `social_account`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `social_account` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `user_id` INT(11) NULL DEFAULT NULL,
          `provider` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
          `client_id` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
          `data` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `code` VARCHAR(32) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `created_at` INT(11) NULL DEFAULT NULL,
          `email` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          `username` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `account_unique` (`provider` ASC, `client_id` ASC),
          UNIQUE INDEX `account_unique_code` (`code` ASC),
          INDEX `fk_user_account` (`user_id` ASC),
          CONSTRAINT `fk_user_account`
            FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `token`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `token` (
          `user_id` INT(11) NOT NULL,
          `code` VARCHAR(32) CHARACTER SET 'utf8' NOT NULL,
          `created_at` INT(11) NOT NULL,
          `type` SMALLINT(6) NOT NULL,
          UNIQUE INDEX `token_unique` (`user_id` ASC, `code` ASC, `type` ASC),
          CONSTRAINT `fk_user_token`
            FOREIGN KEY (`user_id`)
            REFERENCES `user` (`id`)
            ON DELETE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_unicode_ci;


        -- -----------------------------------------------------
        -- Table `question_has_course`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `question_has_course` (
          `question_id` INT(11) NOT NULL,
          `course_id` INT(11) NOT NULL,
          PRIMARY KEY (`question_id`, `course_id`),
          INDEX `fk_question_has_course_course1_idx` (`course_id` ASC),
          INDEX `fk_question_has_course_question1_idx` (`question_id` ASC),
          CONSTRAINT `fk_question_has_course_question1`
            FOREIGN KEY (`question_id`)
            REFERENCES `question` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_question_has_course_course1`
            FOREIGN KEY (`course_id`)
            REFERENCES `course` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `question_has_challenge_type`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `question_has_challenge_type` (
          `question_id` INT(11) NOT NULL,
          `challenge_type_id` INT(11) NOT NULL,
          PRIMARY KEY (`question_id`, `challenge_type_id`),
          INDEX `fk_question_has_challenge_type_challenge_type1_idx` (`challenge_type_id` ASC),
          INDEX `fk_question_has_challenge_type_question1_idx` (`question_id` ASC),
          CONSTRAINT `fk_question_has_challenge_type_question1`
            FOREIGN KEY (`question_id`)
            REFERENCES `question` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_question_has_challenge_type_challenge_type1`
            FOREIGN KEY (`challenge_type_id`)
            REFERENCES `challenge_type` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `question_has_subject`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `question_has_subject` (
          `question_id` INT(11) NOT NULL,
          `subject_id` INT NOT NULL,
          PRIMARY KEY (`question_id`, `subject_id`),
          INDEX `fk_question_has_subject_subject1_idx` (`subject_id` ASC),
          INDEX `fk_question_has_subject_question1_idx` (`question_id` ASC),
          CONSTRAINT `fk_question_has_subject_question1`
            FOREIGN KEY (`question_id`)
            REFERENCES `question` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_question_has_subject_subject1`
            FOREIGN KEY (`subject_id`)
            REFERENCES `subject` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;


        -- -----------------------------------------------------
        -- Table `challenge_has_question`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `challenge_has_question` (
          `challenge_id` INT(11) NOT NULL,
          `question_id` INT(11) NOT NULL,
          `position` INT NULL,
          PRIMARY KEY (`challenge_id`, `question_id`),
          INDEX `fk_challenge_has_question_question1_idx` (`question_id` ASC),
          INDEX `fk_challenge_has_question_challenge1_idx` (`challenge_id` ASC),
          CONSTRAINT `fk_challenge_has_question_challenge1`
            FOREIGN KEY (`challenge_id`)
            REFERENCES `challenge` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `fk_challenge_has_question_question1`
            FOREIGN KEY (`question_id`)
            REFERENCES `question` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8;
        ");
    }

    public function down()
    {
        echo "m160728_200842_init_schema cannot be reverted.\n";

        return false;
    }
}