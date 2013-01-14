/* ALL SQL QUERY STORED IN THIS FILE */

DROP PROCEDURE IF EXISTS CreateUser;
DROP PROCEDURE IF EXISTS ValidateUser;
DROP PROCEDURE IF EXISTS GetUser;
DROP PROCEDURE IF EXISTS CreateTaskGroup;
DROP PROCEDURE IF EXISTS UpdateTaskGroup;
DROP PROCEDURE IF EXISTS UpdateTaskGroupOrder;
DROP PROCEDURE IF EXISTS DeleteTaskGroup;
DROP PROCEDURE IF EXISTS CreateTask;
DROP PROCEDURE IF EXISTS DeleteTask;
DROP PROCEDURE IF EXISTS UpdateTask;
DROP PROCEDURE IF EXISTS GetAllMyTasks;
DROP PROCEDURE IF EXISTS GetTask;
DROP PROCEDURE IF EXISTS GetFriends;

/* CREATE A USER */
DELIMITER // 
CREATE PROCEDURE CreateUser(
IN myusername char(20),
IN mypasswd char(32),
IN myfirstname char(30),
IN mylastname char(30),
IN myemail char(50),
IN myavatar int
) 
BEGIN 
INSERT INTO USER (username, passwd, firstname, lastname, email, avatar)
VALUES(myusername, mypasswd, myfirstname, mylastname, myemail, myavatar);
END // 
DELIMITER ;

/* validate a user */
DELIMITER // 
CREATE PROCEDURE ValidateUser(
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
CREATE PROCEDURE GetUser(
IN myuid int
) 
BEGIN 
SELECT * FROM USER
WHERE uid = myuid;
END // 
DELIMITER ;

/* CREATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE CreateTaskGroup(
IN myuid int,
IN mytitle char(40),
IN mypri int,
IN mytype int,
IN mytorder varchar(65535)
) 
BEGIN 
INSERT INTO T_GROUP (uid, title, priority, type, t_order)
VALUES(myuid, mytitle, mypri, mytype, mytorder);
END // 
DELIMITER ;

/* UPDATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE UpdateTaskGroup(
IN mytgid int,
IN mytitle char(40),
IN mypri int,
IN mytype int,
IN mytorder varchar(65535)
) 
BEGIN 
UPDATE T_GROUP
SET title = mytitle, priority = mypri,
type = mytype, t_order = mytorder
WHERE tgid = mytgid;
END // 
DELIMITER ;

/* UPDATE A TASK GROUP TASK ORDER */
DELIMITER // 
CREATE PROCEDURE UpdateTaskGroupOrder(
IN mytgid int,
IN mytorder varchar(65535)
) 
BEGIN 
UPDATE T_GROUP
SET t_order = mytorder
WHERE tgid = mytgid;
END // 
DELIMITER ;

/* DELETE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE DeleteTaskGroup(
IN mytgid int
) 
BEGIN 
DELETE FROM T_GROUP
WHERE T_GROUP.tgid = mytgid;
END // 
DELIMITER ;

/* CREATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE CreateTask(
IN myuid int,
IN myotid int,
IN mytgid int,
IN mycontent char(140)
) 
BEGIN 
INSERT INTO TASK (uid, otid, tgid, content)
VALUES(myuid, myotid, mytgid, mycontent);
END // 
DELIMITER ;

/* DELETE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE DeleteTask(
IN mytid int
) 
BEGIN 
DELETE FROM TASK
WHERE TASK.tid = mytid;
END // 
DELIMITER ;

/* UPDATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE UpdateTask(
IN mytid int,
IN mycontent char(140),
IN myprivacy int
) 
BEGIN 
UPDATE TASK
SET TASK.content = mycontent,
TASK.privacy = myprivacy
WHERE TASK.tid = mytid;
END // 
DELIMITER ;

/* GET ALL TASKS BY USER */
DELIMITER // 
CREATE PROCEDURE GetAllMyTasks(
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
  FROM T_GROUP RIGHT JOIN USER
  ON T_GROUP.uid = USER.uid
  WHERE USER.uid = myuid
) utg
LEFT JOIN TASK
ON
TASK.tgid = utg.tgid
LEFT JOIN EXP
ON TASK.tid = EXP.tid
GROUP BY pk
ORDER BY priority DESC,tgid DESC, ts DESC;
END // 
DELIMITER ;

/* GET A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE GetTask(
IN mytid int
) 
BEGIN
SELECT TASK.tid, TASK.uid, USER.username,
USER.firstname, USER.lastname, TASK.content,
COUNT(EXP.expid) AS expcount, TASK.ts, TASK.isdone, TASK.privacy
FROM TASK LEFT JOIN USER
ON TASK.uid = USER.uid
LEFT JOIN EXP
ON TASK.tid = EXP.tid
WHERE TASK.tid = mytid
GROUP BY EXP.tid;
END // 
DELIMITER ;

/* GET ALL MY FRIENDS */
DELIMITER // 
CREATE PROCEDURE GetFriends(
IN myuid int
) 
BEGIN

SELECT * 
FROM
(
  SELECT *
  FROM FRIEND
  WHERE uid = myuid
) ft
LEFT JOIN USER
ON USER.uid = ft.fuid;
END // 
DELIMITER ;
