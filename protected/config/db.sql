DROP DATABASE IF EXISTS gallery;
CREATE DATABASE gallery;
USE gallery;

CREATE TABLE IF NOT EXISTS user (
	id int(10) unsigned NOT NULL auto_increment,
	username varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	email varchar(128) NULL,
	create_time int(10) NOT NULL,
	last_login_time int(10) NULL,
	last_logout_time int(10) NULL,
	last_login_ip varchar(16) NULL,
	is_active tinyint(1) NOT NULL DEFAULT 1,
	is_admin tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (id, username, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS album (
	id int(10) unsigned NOT NULL auto_increment,
	name varchar(255) NOT NULL,
	description varchar(255) NOT NULL,
	create_time int(10) NULL,
	is_public tinyint(1) NOT NULL DEFAULT 1,
    user_id int(10) unsigned NOT NULL,
	PRIMARY KEY (id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS image (
	id int(10) unsigned NOT NULL auto_increment,
    name varchar(40) NOT NULL,
	description varchar(255) NOT NULL,
    create_time int(10) NULL,
	is_public tinyint(1) NOT NULL DEFAULT 1,
    user_id int(10) unsigned NOT NULL,
    album_id int(10) unsigned NOT NULL,
    PRIMARY KEY (id, user_id, album_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS comment (
	id int(10) unsigned NOT NULL auto_increment,
	body varchar(255) NOT NULL,
    create_time int(10) NULL,
    user_id int(10) unsigned NOT NULL,
    image_id int(10) unsigned NOT NULL,
    PRIMARY KEY (id, image_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS info (
	category varchar(10) NOT NULL,
	content varchar(255) NOT NULL,
	album_id int(10) NULL,
	image_name varchar(40) NULL,
    user_id int(10) unsigned NOT NULL,
	PRIMARY KEY (album_id, image_name, user_id),
	FULLTEXT (content)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
