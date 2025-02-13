DROP SCHEMA IF EXISTS anoes;
CREATE SCHEMA anoes;
use anoes;

CREATE TABLE
    anao (
        `id` bigint primary key auto_increment,
        `name` varchar(255) not null,
        `age` smallint unsigned not null,
        `race` tinyint unsigned not null,
        `height` decimal(3, 2) not null,
        `is_gay` tinyint (1) not null default 0
    );

CREATE TABLE
    parceiro (
        `id` bigint primary key auto_increment,
        `name` varchar(255) not null,
        `contact` VARCHAR(255) not null,
        `is_anao` tinyint (1) not null,
        `is_gay` tinyint (1) not null default 0,
        `id_anao` bigint not null,
        foreign key (`id_anao`) references anao(`id`) 
    );

CREATE TABLE users(
    `id` bigint primary key auto_increment,
    `login` varchar(255) not null,
    `password` varchar(255) not null
);