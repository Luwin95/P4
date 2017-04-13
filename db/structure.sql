drop table if exists t_chapter;
drop table if exists t_user;

create table t_chapter (
    chapter_id integer not null primary key auto_increment,
    chapter_title varchar(100) not null,
    chapter_content longtext not null,
    chapter_publishment boolean not null
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_user (
    user_id integer not null primary key auto_increment,
    user_name varchar(50) not null,
    user_password varchar(88) not null,
    user_salt varchar(23) not null,
    user_role varchar(50) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;
