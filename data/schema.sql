DROP
DATABASE IF EXISTS `projectUA`;
CREATE
DATABASE `projectUA` COLLATE utf8_general_ci;
USE `projectUA`;

CREATE TABLE `tender`
(
    id           int(11) NOT NULL AUTO_INCREMENT,
    tenderId     varchar(32)  NOT NULL,
    description  text NOT NULL,
    amount       double       NOT NULL,
    dateModified timestamp    NOT NULL,

    PRIMARY KEY (id),
    UNIQUE INDEX (id),
    UNIQUE INDEX (tenderId)
) engine=InnoDB;
