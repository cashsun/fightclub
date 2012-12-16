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
IN uid int,
IN tgid int
) 
BEGIN 
SELECT * FROM FIGHTDB.O_TASK
WHERE O_TASK.uid = uid
AND O_TASK.tgid = tgid;
END // 
DELIMITER ;