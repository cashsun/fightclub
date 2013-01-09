/* ALL SQL QUERY STORED IN THIS FILE */
/* CREATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateTaskGroup(
IN myuid int,
IN mytitle char(10)
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
WHERE O_TASK.tid = mytid;
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