CREATE DATABASE IF NOT EXISTS phonebook;
USE phonebook;
create table `user`
(
    id         int auto_increment primary key,
    login      varchar(50) not null,
    email      varchar(50) not null,
    password   varchar(32) not null,
    created_at datetime    not null,
    constraint user_email_uindex
        unique (email),
    constraint user_login_uindex
        unique (login)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `phone`
(
    `id`         int(11)     NOT NULL AUTO_INCREMENT,
    `user_id`    int(11)     NOT NULL,
    `phone`      varchar(12) NOT NULL,
    `first_name` varchar(20) DEFAULT NULL,
    `last_name`  varchar(20) DEFAULT NULL,
    `email`      varchar(30) DEFAULT NULL,
    `file`       varchar(40) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

ALTER TABLE `phone`
    ADD CONSTRAINT `phone_user_fk` FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;