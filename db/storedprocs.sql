/* ALL SQL QUERY STORED IN THIS FILE */

DROP PROCEDURE IF EXISTS FIGHTDB.CreateUser;
DROP PROCEDURE IF EXISTS FIGHTDB.ValidateUser;
DROP PROCEDURE IF EXISTS FIGHTDB.GetUser;
DROP PROCEDURE IF EXISTS FIGHTDB.CreateTaskGroup;
DROP PROCEDURE IF EXISTS FIGHTDB.UpdateTaskGroup;
DROP PROCEDURE IF EXISTS FIGHTDB.DeleteTaskGroup;
DROP PROCEDURE IF EXISTS FIGHTDB.CreateTask;
DROP PROCEDURE IF EXISTS FIGHTDB.DeleteTask;
DROP PROCEDURE IF EXISTS FIGHTDB.UpdateTask;
DROP PROCEDURE IF EXISTS FIGHTDB.GetAllMyTasks;
DROP PROCEDURE IF EXISTS FIGHTDB.GetTask;
DROP PROCEDURE IF EXISTS FIGHTDB.GetFriends;

/* CREATE A USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateUser(
IN myusername char(20),
IN mypasswd char(32),
IN myfirstname char(30),
IN mylastname char(30),
IN myemail char(50),
IN myavatar int
) 
BEGIN 
INSERT INTO FIGHTDB.USER (username, passwd, firstname, lastname, email, avatar)
VALUES(myusername, mypasswd, myfirstname, mylastname, myemail, myavatar);
END // 
DELIMITER ;

/* validate a user */
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

/* get a user */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetUser(
IN myuid int
) 
BEGIN 
SELECT * FROM USER
WHERE uid = myuid;
END // 
DELIMITER ;

/* CREATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateTaskGroup(
IN myuid int,
IN mytitle char(40),
IN mypri int,
IN mytype int,
IN mytorder varchar(65535)
) 
BEGIN 
INSERT INTO FIGHTDB.T_GROUP (uid, title, priority, type, t_order)
VALUES(myuid, mytitle, mypri, mytype, mytorder);
END // 
DELIMITER ;

/* UPDATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.UpdateTaskGroup(
IN mytgid int,
IN mytitle char(40),
IN mypri int,
IN mytype int,
IN mytorder varchar(65535)
) 
BEGIN 
UPDATE FIGHTDB.T_GROUP
SET title = mytitle, priority = mypri
WHERE tgid = mytgid, type = mytype,
t_order = mytorder;
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

/* UPDATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.UpdateTask(
IN mytid int,
IN mycontent char(140),
IN myprivacy int
) 
BEGIN 
UPDATE FIGHTDB.TASK
SET TASK.content = mycontent,
TASK.privacy = myprivacy
WHERE TASK.tid = mytid;
END // 
DELIMITER ;

/* GET ALL TASKS BY USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetAllMyTasks(
IN myuid int
) 
BEGIN

SELECT TASK.tid, TASK.otid, utg.uid, utg.username,
utg.firstname, utg.lastname, utg.email, TASK.content,
COUNT(EXP.expid) AS expcount, TASK.ts, TASK.isdone,
utg.tgid, utg.priority, utg.title, utg.exp, TASK.privacy,
CONCAT(CONCAT(IFNULL(TASK.tid, 'NULL'), ' '),utg.tgid) AS pk
FROM
(
  SELECT T_GROUP.tgid, T_GROUP.priority,
  T_GROUP.title, USER.uid, USER.username,
  USER.firstname, USER.lastname, USER.email, USER.exp
  FROM FIGHTDB.T_GROUP RIGHT JOIN FIGHTDB.USER
  ON T_GROUP.uid = USER.uid
  WHERE USER.uid = myuid
) utg
LEFT JOIN FIGHTDB.TASK
ON
TASK.tgid = utg.tgid
LEFT JOIN FIGHTDB.EXP
ON TASK.tid = EXP.tid
GROUP BY pk
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
COUNT(EXP.expid) AS expcount, TASK.ts, TASK.isdone, TASK.privacy
FROM FIGHTDB.TASK LEFT JOIN FIGHTDB.USER
ON TASK.uid = USER.uid
LEFT JOIN FIGHTDB.EXP
ON TASK.tid = EXP.tid
WHERE TASK.tid = mytid
GROUP BY EXP.tid;
END // 
DELIMITER ;

/* GET ALL MY FRIENDS */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetFriends(
IN myuid int
) 
BEGIN

SELECT * 
FROM
(
  SELECT *
  FROM FIGHTDB.FRIEND
  WHERE uid = myuid
) ft
LEFT JOIN FIGHTDB.USER
ON USER.uid = ft.fuid;
END // 
DELIMITER ;
