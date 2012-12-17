/* ALL SQL QUERY STORED IN THIS FILE */
/* CREATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE CreateOriTask(
IN uid int,
IN tgid int,
IN content char(140)
) 
BEGIN 
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content)
VALUES(uid, tgid, content);
END // 
DELIMITER ;

/* LIST A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE ListOriTask(
IN myuid int,
IN mytgid int
) 
BEGIN
SELECT O_TASK.tid, O_TASK.uid, USER.username,
USER.firstname, USER.lastname, O_TASK.content,
COUNT(EXP.exp) AS expcount, O_TASK.ts, O_TASK.isDone, EXP.isOt
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