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
title char(10) NOT NULL,
ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
priority int DEFAULT 0 NOT NULL,
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
isdone BOOLEAN DEFAULT FALSE,
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
isdone BOOLEAN DEFAULT FALSE,
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
isot BOOLEAN NOT NULL DEFAULT TRUE,
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


/* ALL SQL QUERY STORED IN THIS FILE */
/* CREATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateOriTask(
IN uid int,
IN tgid int,
IN content char(140)
) 
BEGIN 
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content)
VALUES(uid, tgid, content);
END // 
DELIMITER ;

/* DELETE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.DeleteOriTask(
IN mytid int
) 
BEGIN 
DELETE FROM FIGHTDB.O_TASK
WHERE O_TASK.tid = mytid;
END // 
DELIMITER ;

/* LIST ORIGINAL TO-DO TASKS */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.ListOriTask(
IN myuid int,
IN mytgid int
) 
BEGIN
SELECT O_TASK.tid, O_TASK.uid, USER.username,
USER.firstname, USER.lastname, O_TASK.content,
COUNT(EXP.expid) AS expcount, O_TASK.ts, O_TASK.isdone, EXP.isOt
FROM FIGHTDB.O_TASK LEFT JOIN FIGHTDB.USER
ON O_TASK.uid = USER.uid
LEFT JOIN FIGHTDB.EXP
ON O_TASK.tid = EXP.tid
AND
Exp.isOt = TRUE
WHERE O_TASK.uid = myuid
AND O_TASK.tgid = mytgid
GROUP BY EXP.tid;
END // 
DELIMITER ;

/* GET A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetOriTask(
IN mytid int
) 
BEGIN
SELECT O_TASK.tid, O_TASK.uid, USER.username,
USER.firstname, USER.lastname, O_TASK.content,
COUNT(EXP.expid) AS expcount, O_TASK.ts, O_TASK.isdone, EXP.isOt
FROM FIGHTDB.O_TASK LEFT JOIN FIGHTDB.USER
ON O_TASK.uid = USER.uid
LEFT JOIN FIGHTDB.EXP
ON O_TASK.tid = EXP.tid
AND
Exp.isOt = TRUE
WHERE O_TASK.tid = mytid
GROUP BY EXP.tid;
END // 
DELIMITER ;


/* GET ORI TASKS BY USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetAllMyOriTasks(
IN myuid int
) 
BEGIN
SELECT O_TASK.tid, O_TASK.uid, utg.username,
utg.firstname, utg.lastname, utg.email, O_TASK.content,
COUNT(EXP.expid) AS expcount, O_TASK.ts, O_TASK.isdone,
IFNULL(EXP.isOt, TRUE), utg.tgid, utg.priority, utg.title, utg.exp
FROM
(
  SELECT T_GROUP.tgid, T_GROUP.priority,
  T_GROUP.title, T_GROUP.uid, USER.username,
  USER.firstname, USER.lastname, USER.email, USER.exp
  FROM FIGHTDB.T_GROUP LEFT JOIN FIGHTDB.USER
  ON T_GROUP.uid = USER.uid
  WHERE T_GROUP.uid = myuid
) utg 
LEFT JOIN FIGHTDB.O_TASK
ON
O_TASK.tgid = utg.tgid
LEFT JOIN FIGHTDB.EXP
ON O_TASK.tid = EXP.tid
AND
Exp.isOt = TRUE
GROUP BY O_TASK.tid
ORDER BY utg.priority DESC, utg.tgid ASC, O_TASK.ts DESC;
END // 
DELIMITER ;


/* GET REPO TASKS BY USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetAllMyRepoTasks(
IN myuid int
) 
BEGIN
SELECT urt.rtid, urt.username,
urt.firstname, urt.lastname, urt.email, O_TASK.content,
COUNT(EXP.expid) AS expcount, urt.ts, urt.isdone,
IFNULL(EXP.isOt, FALSE), urt.tgid, urt.priority, urt.title, urt.exp
FROM
(
  SELECT * FROM
  (
    SELECT T_GROUP.tgid AS utgid, T_GROUP.uid AS utuid,
    T_GROUP.priority, T_GROUP.title, USER.username,
    USER.firstname, USER.lastname, USER.email, USER.exp
    FROM FIGHTDB.T_GROUP LEFT JOIN FIGHTDB.USER
    ON T_GROUP.uid = USER.uid
    WHERE T_GROUP.uid = myuid
  ) utg 
  JOIN FIGHTDB.R_TASK
  ON
  R_TASK.tgid = utg.utgid
  AND
  R_TASK.uid = utg.utuid
) urt
LEFT JOIN FIGHTDB.O_TASK
ON
O_TASK.tid = urt.otid
LEFT JOIN FIGHTDB.EXP
ON urt.rtid = EXP.tid
AND
Exp.isOt = FALSE
GROUP BY urt.rtid
ORDER BY urt.priority DESC, urt.tgid ASC, O_TASK.ts DESC;
END // 
DELIMITER ;


/* GET ALL TASKS BY USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetAllMyTasks(
IN myuid int
) 
BEGIN

(
  SELECT urt.rtid AS tid, urt.uid, urt.username,
  urt.firstname, urt.lastname, urt.email, O_TASK.content,
  COUNT(EXP.expid) AS expcount, urt.ts, urt.isdone,
  IFNULL(EXP.isOt, FALSE), urt.tgid, urt.priority, urt.title, urt.exp
  FROM
  (
    SELECT * FROM
    (
      SELECT T_GROUP.tgid AS utgid, T_GROUP.uid AS utuid,
      T_GROUP.priority, T_GROUP.title, USER.username,
      USER.firstname, USER.lastname, USER.email, USER.exp
      FROM FIGHTDB.T_GROUP LEFT JOIN FIGHTDB.USER
      ON T_GROUP.uid = USER.uid
      WHERE T_GROUP.uid = myuid
    ) utg 
    JOIN FIGHTDB.R_TASK
    ON
    R_TASK.tgid = utg.utgid
    AND
    R_TASK.uid = utg.utuid
  ) urt
  LEFT JOIN FIGHTDB.O_TASK
  ON
  O_TASK.tid = urt.otid
  LEFT JOIN FIGHTDB.EXP
  ON urt.rtid = EXP.tid
  AND
  Exp.isOt = FALSE
  GROUP BY urt.rtid
)
UNION
(
  SELECT O_TASK.tid, O_TASK.uid, uotg.username,
  uotg.firstname, uotg.lastname, uotg.email, O_TASK.content,
  COUNT(EXP.expid) AS expcount, O_TASK.ts, O_TASK.isdone,
  IFNULL(EXP.isOt, TRUE), uotg.tgid, uotg.priority, uotg.title, uotg.exp
  FROM
  (
    SELECT T_GROUP.tgid, T_GROUP.priority,
    T_GROUP.title, T_GROUP.uid, USER.username,
    USER.firstname, USER.lastname, USER.email, USER.exp
    FROM FIGHTDB.T_GROUP LEFT JOIN FIGHTDB.USER
    ON T_GROUP.uid = USER.uid
    WHERE T_GROUP.uid = myuid
  ) uotg 
  LEFT JOIN FIGHTDB.O_TASK
  ON
  O_TASK.tgid = uotg.tgid
  LEFT JOIN FIGHTDB.EXP
  ON O_TASK.tid = EXP.tid
  AND
  Exp.isOt = TRUE
  GROUP BY O_TASK.tid
)
ORDER BY priority DESC,tgid, ts DESC;
END // 
DELIMITER ;