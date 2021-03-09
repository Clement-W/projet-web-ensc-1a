create database if not exists annuaireEleves character set utf8 collate utf8_unicode_ci;
use annuaireEleves;

grant all privileges on annuaireEleves.* to 'annuaireUser'@'localhost' identified by 'explodingkittens';
