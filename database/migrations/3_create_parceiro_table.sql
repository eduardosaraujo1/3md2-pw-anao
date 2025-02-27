CREATE TABLE
    parceiro (
        `id` bigint primary key auto_increment,
        `name` varchar(255) not null,
        `contact` VARCHAR(255) not null,
        `is_anao` tinyint (1) not null,
        `id_anao` bigint not null,
        foreign key (`id_anao`) references anao(`id`) 
    );
