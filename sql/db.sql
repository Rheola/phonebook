create table user
(
    id         int auto_increment
        primary key,
    login      varchar(50) not null,
    email      varchar(50) null,
    password   varchar(32) not null,
    created_at datetime    not null,
    constraint user_email_uindex
        unique (email),
    constraint user_login_uindex
        unique (login)
);

