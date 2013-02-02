DROP SCHEMA IF EXISTS FIGHTDB; 


CREATE DATABASE FIGHTDB
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

set time_zone = '+00:00';

CREATE TABLE USER
(
uid int NOT NULL AUTO_INCREMENT,
exp int NOT NULL,
username char(20) NOT NULL,
passwd char(32) NOT NULL,
firstname char(30),
lastname char(30),
email char(50),
CONSTRAINT pk_uid PRIMARY KEY (uid)
);

CREATE TABLE FRIEND
(
frid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
fuid int NOT NULL,
CONSTRAINT pk_frid PRIMARY KEY (frid)
);

CREATE TABLE T_GROUP
(
tgid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
title char(40) NOT NULL,
tstamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
priority int DEFAULT 0 NOT NULL,
CONSTRAINT pk_t_gid PRIMARY KEY (tgid),
CONSTRAINT fk_t_uid FOREIGN KEY (uid) REFERENCES USER(uid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE TASK
(
tid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
otid int NOT NULL DEFAULT 0,
tgid int NOT NULL,
content char(140),
tstamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
isdone BOOLEAN DEFAULT FALSE,
privacy int NOT NULL DEFAULT 0,
lastupdate TIMESTAMP NOT NULL,
CONSTRAINT pk_otask_tid PRIMARY KEY (tid),
CONSTRAINT fk_otask_uid FOREIGN KEY (uid) REFERENCES USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_otask_tgid FOREIGN KEY (tgid) REFERENCES T_GROUP(tgid) ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE EXP
(
expid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
tid int NOT NULL,
tstamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
CONSTRAINT pk_expid PRIMARY KEY (expid),
CONSTRAINT fk_exp_uid FOREIGN KEY (uid) REFERENCES USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_exp_tid FOREIGN KEY (tid) REFERENCES TASK(tid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE EVENT
(
eventid int NOT NULL AUTO_INCREMENT,
actionid int NOT NULL,
uid int NOT NULL,
tid int,
fuid int,
tstamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
CONSTRAINT pk_eventid PRIMARY KEY (eventid)
);

CREATE TABLE COMMENT
(
commentid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
tid int NOT NULL,
content char(140),
tstamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
CONSTRAINT pk_commentid PRIMARY KEY (commentid),
CONSTRAINT fk_comment_uid FOREIGN KEY (uid) REFERENCES USER(uid),
CONSTRAINT fk_comment_tid FOREIGN KEY (tid) REFERENCES TASK(tid)
);

INSERT INTO USER (username, passwd, firstname, lastname, email) VALUES('plutoless', MD5('test'), 'Qianze', 'Zhang', 'qz@gmail.com');
INSERT INTO USER (username, passwd, firstname, lastname, email) VALUES('cashsun', MD5('test'), 'Cash', 'Sun', 'cs@gmail.com');

INSERT INTO T_GROUP (uid, title) VALUES('1', 'game');
INSERT INTO T_GROUP (uid, title) VALUES('1', 'IT');
INSERT INTO T_GROUP (uid, title) VALUES('2', 'daily');

INSERT INTO TASK (uid, otid, tgid, content) VALUES('1','1','2', 'IT CONTENT');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('1','2','2', 'IT CONTENT2');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('1','3','2', 'IT CONTENT3');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('1','4','1', 'GAME CONTENT');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('1','5','1', 'GAME CONTENT2');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('1','6','1', 'GAME CONTENT3');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('2','7','3', 'DAILY CONTENT');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('2','8','3', 'DAILY CONTENT2');
INSERT INTO TASK (uid, otid, tgid, content) VALUES('2','9','3', 'DAILY CONTENT3');

/* REPO */
INSERT INTO TASK (uid, otid, tgid, content) VALUES('2','5','3', 'DAILY CONTENT3');

INSERT INTO FRIEND (uid, fuid) VALUES('1','2');

ALTER TABLE USER ADD avatar int NOT NULL DEFAULT 0;
ALTER TABLE T_GROUP ADD type int NOT NULL DEFAULT 0;
ALTER TABLE T_GROUP ADD t_order TEXT;
ALTER TABLE USER ADD newststamp TIMESTAMP;
ALTER TABLE EVENT ADD tgid int;
ALTER TABLE EVENT ADD ctstamp TIMESTAMP;
ALTER TABLE TASK ADD deadline TIMESTAMP;
ALTER TABLE EVENT change fuid uid2 int;
ALTER TABLE EVENT change uid uid1 int;
ALTER TABLE EVENT add cid int;
ALTER TABLE EVENT change actionid eventtype int;