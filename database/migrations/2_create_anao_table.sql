CREATE TABLE
    anao (
        `id` bigint primary key auto_increment,
        `name` varchar(255) not null,
        `age` smallint unsigned not null,
        `race` tinyint unsigned not null,
        `height` decimal(3, 2) not null
    );
