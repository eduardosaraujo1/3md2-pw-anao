CREATE TABLE users(
    `id` bigint primary key auto_increment,
    `login` varchar(255) not null,
    `password` varchar(255) not null
);