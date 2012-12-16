CREATE DATABASE FIGHTDB
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

CREATE TABLE FIGHTDB.USER
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

CREATE TABLE FIGHTDB.FRIEND
(
frid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
fuid int NOT NULL,
CONSTRAINT pk_frid PRIMARY KEY (frid)
);

CREATE TABLE FIGHTDB.T_GROUP
(
tgid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
title char(10) NOT NULL,
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
CONSTRAINT pk_t_gid PRIMARY KEY (tgid),
CONSTRAINT fk_t_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FIGHTDB.O_TASK
(
tid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
tgid int NOT NULL,
content char(140),
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
isDone BOOLEAN DEFAULT FALSE,
CONSTRAINT pk_otask_tid PRIMARY KEY (tid),
CONSTRAINT fk_otask_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_otask_tgid FOREIGN KEY (tgid) REFERENCES FIGHTDB.T_GROUP(tgid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FIGHTDB.R_TASK
(
rtid int NOT NULL AUTO_INCREMENT,
otid int NOT NULL,
uid int NOT NULL,
tgid int NOT NULL,
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
isDone BOOLEAN DEFAULT FALSE,
CONSTRAINT pk_rtask_tid PRIMARY KEY (rtid),
CONSTRAINT fk_rtask_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_rtask_tgid FOREIGN KEY (tgid) REFERENCES FIGHTDB.T_GROUP(tgid) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE FIGHTDB.EXP
(
expid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
tid int NOT NULL,
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
isOt BOOLEAN NOT NULL,
CONSTRAINT pk_expid PRIMARY KEY (expid),
CONSTRAINT fk_exp_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_exp_tid FOREIGN KEY (tid) REFERENCES FIGHTDB.O_TASK(tid) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO FIGHTDB.USER (username, passwd, firstname, lastname, email) VALUES('plutoless', 'test', 'Qianze', 'Zhang', 'qz@gmail.com');
INSERT INTO FIGHTDB.USER (username, passwd, firstname, lastname, email) VALUES('cashsun', 'test', 'Cash', 'Sun', 'cs@gmail.com');

INSERT INTO FIGHTDB.T_GROUP (uid, title) VALUES('1', 'game');
INSERT INTO FIGHTDB.T_GROUP (uid, title) VALUES('1', 'IT');
INSERT INTO FIGHTDB.T_GROUP (uid, title) VALUES('2', 'daily');

INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('1','2', 'IT CONTENT');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('1','2', 'IT CONTENT2');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('1','2', 'IT CONTENT3');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('1','1', 'GAME CONTENT');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('1','1', 'GAME CONTENT2');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('1','1', 'GAME CONTENT3');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('2','3', 'DAILY CONTENT');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('2','3', 'DAILY CONTENT2');
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content) VALUES('2','3', 'DAILY CONTENT3');


INSERT INTO FIGHTDB.R_TASK (otid, uid, tgid) VALUES('5','2','3');
INSERT INTO FIGHTDB.FRIEND (uid, fuid) VALUES('1','2');
