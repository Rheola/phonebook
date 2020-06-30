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

create table phonebook.phone
(
    id int auto_increment
        primary key,
    user_id int not null,
    phone varchar(12) not null,
    first_name varchar(20) null,
    last_name varchar(20) null,
    email varchar(30) null,
    file varchar(40) null
)
    charset=utf8;

