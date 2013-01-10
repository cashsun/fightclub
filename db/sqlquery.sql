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
