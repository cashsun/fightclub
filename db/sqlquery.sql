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
CREATE PROCEDURE FIGHTDB.CreateOriTask(
IN myuid int,
IN mytgid int,
IN mycontent char(140)
) 
BEGIN 
INSERT INTO FIGHTDB.O_TASK (uid, tgid, content)
VALUES(myuid, mytgid, mycontent);
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


/* CREATE A REPO TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.CreateRepoTask(
IN myuid int,
IN mytgid int,
IN myotid int
) 
BEGIN 
INSERT INTO FIGHTDB.R_TASK (uid, tgid, otid)
VALUES(myuid, mytgid, myotid);
END // 
DELIMITER ;

/* DELETE A REPO TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE FIGHTDB.DeleteRepoTask(
IN myrtid int
) 
BEGIN 
DELETE FROM FIGHTDB.R_TASK
WHERE R_TASK.rtid = myrtid;
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