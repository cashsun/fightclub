DROP SCHEMA IF EXISTS FIGHTDB; 

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
title char(40) NOT NULL,
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
priority int DEFAULT 0 NOT NULL,
CONSTRAINT pk_t_gid PRIMARY KEY (tgid),
CONSTRAINT fk_t_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FIGHTDB.TASK
(
tid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
otid int NOT NULL DEFAULT 0,
tgid int NOT NULL,
content char(140),
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
isdone BOOLEAN DEFAULT FALSE,
privacy int NOT NULL DEFAULT 0,
CONSTRAINT pk_otask_tid PRIMARY KEY (tid),
CONSTRAINT fk_otask_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_otask_tgid FOREIGN KEY (tgid) REFERENCES FIGHTDB.T_GROUP(tgid) ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE FIGHTDB.EXP
(
expid int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
tid int NOT NULL,
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
CONSTRAINT pk_expid PRIMARY KEY (expid),
CONSTRAINT fk_exp_uid FOREIGN KEY (uid) REFERENCES FIGHTDB.USER(uid) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fk_exp_tid FOREIGN KEY (tid) REFERENCES FIGHTDB.TASK(tid) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO FIGHTDB.USER (username, passwd, firstname, lastname, email) VALUES('plutoless', MD5('test'), 'Qianze', 'Zhang', 'qz@gmail.com');
INSERT INTO FIGHTDB.USER (username, passwd, firstname, lastname, email) VALUES('cashsun', MD5('test'), 'Cash', 'Sun', 'cs@gmail.com');

INSERT INTO FIGHTDB.T_GROUP (uid, title) VALUES('1', 'game');
INSERT INTO FIGHTDB.T_GROUP (uid, title) VALUES('1', 'IT');
INSERT INTO FIGHTDB.T_GROUP (uid, title) VALUES('2', 'daily');

INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('1','1','2', 'IT CONTENT');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('1','2','2', 'IT CONTENT2');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('1','3','2', 'IT CONTENT3');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('1','4','1', 'GAME CONTENT');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('1','5','1', 'GAME CONTENT2');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('1','6','1', 'GAME CONTENT3');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('2','7','3', 'DAILY CONTENT');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('2','8','3', 'DAILY CONTENT2');
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('2','9','3', 'DAILY CONTENT3');

/* REPO */
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content) VALUES('2','5','3', 'DAILY CONTENT3');

INSERT INTO FIGHTDB.FRIEND (uid, fuid) VALUES('1','2');

/* ALL SQL QUERY STORED IN THIS FILE */
/* CREATE A USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateUser(
IN myusername char(20),
mypasswd char(32),
myfirstname char(30),
mylastname char(30),
myemail char(50)
) 
BEGIN 
INSERT INTO FIGHTDB.USER (username, passwd, firstname, lastname, email)
VALUES(myusername, mypasswd, myfirstname, mylastname, myemail);
END // 
DELIMITER ;

/* CREATE A USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.ValidateUser(
IN myusername char(20),
mypasswd char(32)
) 
BEGIN 
SELECT * FROM USER
WHERE username = myusername
AND
passwd = mypasswd;
END // 
DELIMITER ;

/* CREATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateTaskGroup(
IN myuid int,
IN mytitle char(40)
) 
BEGIN 
INSERT INTO FIGHTDB.T_GROUP (uid, title)
VALUES(myuid, mytitle);
END // 
DELIMITER ;

/* DELETE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.DeleteTaskGroup(
IN mytgid int
) 
BEGIN 
DELETE FROM FIGHTDB.T_GROUP
WHERE T_GROUP.tgid = mytgid;
END // 
DELIMITER ;

/* CREATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateTask(
IN myuid int,
IN myotid int,
IN mytgid int,
IN mycontent char(140)
) 
BEGIN 
INSERT INTO FIGHTDB.TASK (uid, otid, tgid, content)
VALUES(myuid, myotid, mytgid, mycontent);
END // 
DELIMITER ;

/* DELETE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.DeleteTask(
IN mytid int
) 
BEGIN 
DELETE FROM FIGHTDB.TASK
WHERE TASK.tid = mytid;
END // 
DELIMITER ;

/* GET ALL TASKS BY USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetAllMyTasks(
IN myuid int
) 
BEGIN

SELECT TASK.tid, TASK.otid, TASK.uid, utg.username,
utg.firstname, utg.lastname, utg.email, TASK.content,
COUNT(EXP.expid) AS expcount, TASK.ts, TASK.isdone,
utg.tgid, utg.priority, utg.title, utg.exp
FROM
(
  SELECT T_GROUP.tgid, T_GROUP.priority,
  T_GROUP.title, T_GROUP.uid, USER.username,
  USER.firstname, USER.lastname, USER.email, USER.exp
  FROM FIGHTDB.T_GROUP LEFT JOIN FIGHTDB.USER
  ON T_GROUP.uid = USER.uid
  WHERE T_GROUP.uid = myuid
) utg
LEFT JOIN FIGHTDB.TASK
ON
TASK.tgid = utg.tgid
LEFT JOIN FIGHTDB.EXP
ON TASK.tid = EXP.tid
GROUP BY TASK.tid
ORDER BY priority DESC,tgid DESC, ts DESC;
END // 
DELIMITER ;

/* GET A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetTask(
IN mytid int
) 
BEGIN
SELECT TASK.tid, TASK.uid, USER.username,
USER.firstname, USER.lastname, TASK.content,
COUNT(EXP.expid) AS expcount, TASK.ts, TASK.isdone
FROM FIGHTDB.TASK LEFT JOIN FIGHTDB.USER
ON TASK.uid = USER.uid
LEFT JOIN FIGHTDB.EXP
ON TASK.tid = EXP.tid
WHERE TASK.tid = mytid
GROUP BY EXP.tid;
END // 
DELIMITER ;
