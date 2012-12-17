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


/* GET GROUPS BY USER */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.GetAllMyOriTasks(
IN myuid int
) 
BEGIN
SELECT O_TASK.tid, O_TASK.uid, utg.username,
utg.firstname, utg.lastname, utg.email, O_TASK.content,
COUNT(EXP.expid) AS expcount, O_TASK.ts, O_TASK.isdone,
EXP.isOt, utg.tgid, utg.priority, utg.title, utg.exp
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