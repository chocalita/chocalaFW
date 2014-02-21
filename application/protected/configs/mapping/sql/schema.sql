
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- author
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `author`;

CREATE TABLE `author`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(30) NOT NULL,
    `lastname` VARCHAR(30) NOT NULL,
    `phone` VARCHAR(20),
    PRIMARY KEY (`id`)
) ENGINE={InnoDB};

-- ---------------------------------------------------------------------
-- book
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `book`;

CREATE TABLE `book`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `category_id` INTEGER NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `isbn` VARCHAR(20),
    `description` TEXT,
    PRIMARY KEY (`id`),
    INDEX `category_id` (`category_id`),
    CONSTRAINT `fk_book_category`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
) ENGINE={InnoDB};

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    PRIMARY KEY (`id`)
) ENGINE={InnoDB};

-- ---------------------------------------------------------------------
-- phone
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `phone`;

CREATE TABLE `phone`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `trademark_id` INTEGER NOT NULL,
    `name` VARCHAR(50),
    `version` INTEGER NOT NULL,
    `description` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `trademark_id` (`trademark_id`),
    CONSTRAINT `phone_fk1`
        FOREIGN KEY (`trademark_id`)
        REFERENCES `trademark` (`id`)
) ENGINE={InnoDB};

-- ---------------------------------------------------------------------
-- school
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `school`;

CREATE TABLE `school`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50),
    `address` TEXT,
    PRIMARY KEY (`id`)
) ENGINE={InnoDB};

-- ---------------------------------------------------------------------
-- teacher
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `id_school` INTEGER NOT NULL,
    `first_name` VARCHAR(50),
    `last_name` VARCHAR(50),
    `degree` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `id_school` (`id_school`),
    CONSTRAINT `i`
        FOREIGN KEY (`id_school`)
        REFERENCES `school` (`id`)
) ENGINE={InnoDB};

-- ---------------------------------------------------------------------
-- trademark
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `trademark`;

CREATE TABLE `trademark`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `address` VARCHAR(200) NOT NULL,
    `country` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE={InnoDB};

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
