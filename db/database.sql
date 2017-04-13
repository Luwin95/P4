create database if not exists writerblog character set utf8 collate utf8_unicode_ci;
use writerblog;

grant all privileges on writerblog.* to 'writerblog_user'@'localhost' identified by 'WrItErBlOgRoXx';
