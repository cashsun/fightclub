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
DROP PROCEDURE IF EXISTS ToogleTaskComplete;
DROP PROCEDURE IF EXISTS GetAllMyTasks;
DROP PROCEDURE IF EXISTS GetTask;
DROP PROCEDURE IF EXISTS GetMyFollows;
DROP PROCEDURE IF EXISTS GetMyFans;
DROP PROCEDURE IF EXISTS GetMyFriends;
DROP PROCEDURE IF EXISTS AddFriend;
DROP PROCEDURE IF EXISTS UnfollowFriend;
DROP PROCEDURE IF EXISTS SearchUsers;
DROP PROCEDURE IF EXISTS UpdateAvatar;
DROP PROCEDURE IF EXISTS GetAllFriendTasks;

/* CREATE A USER */
DELIMITER // 
CREATE PROCEDURE CreateUser(
IN myusername char(20) CHARACTER SET utf8,
IN mypasswd char(32) CHARACTER SET utf8,
IN myfirstname char(30) CHARACTER SET utf8,
IN mylastname char(30) CHARACTER SET utf8,
IN myemail char(50) CHARACTER SET utf8,
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
IN myusername char(20) CHARACTER SET utf8,
mypasswd char(32) CHARACTER SET utf8
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
SELECT USER.uid,
USER.exp, USER.username,
USER.firstname,
USER.lastname, USER.email,
USER.avatar
FROM USER
WHERE uid = myuid;
END // 
DELIMITER ;

/* CREATE A TASK GROUP */
DELIMITER // 
CREATE PROCEDURE CreateTaskGroup(
IN myuid int,
IN mytitle char(40) CHARACTER SET utf8,
IN mypri int,
IN mytype int,
IN mytorder varchar(65535) CHARACTER SET utf8
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
IN mytitle char(40) CHARACTER SET utf8,
IN mypri int,
IN mytype int,
IN mytorder varchar(65535) CHARACTER SET utf8
) 
BEGIN
UPDATE T_GROUP
SET title = mytitle, priority = mypri,
type = mytype, t_order = mytorder
WHERE tgid = mytgid;
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* UPDATE A TASK GROUP TASK ORDER */
DELIMITER // 
CREATE PROCEDURE UpdateTaskGroupOrder(
IN mytgid int,
IN mytorder varchar(65535) CHARACTER SET utf8
) 
BEGIN 
UPDATE T_GROUP
SET t_order = mytorder
WHERE tgid = mytgid;
SELECT ROW_COUNT() AS rows_affected;
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
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* CREATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE CreateTask(
IN myuid int,
IN myotid int,
IN mytgid int,
IN mycontent char(140) CHARACTER SET utf8
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
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* UPDATE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE UpdateTask(
IN mytid int,
IN mycontent char(140) CHARACTER SET utf8,
IN myprivacy int
) 
BEGIN 
UPDATE TASK
SET TASK.content = mycontent,
TASK.privacy = myprivacy
WHERE TASK.tid = mytid;
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* COMPLETE A ORIGINAL TO-DO TASK */
DELIMITER // 
CREATE PROCEDURE ToogleTaskComplete(
IN mytid int,
IN myisdone boolean
) 
BEGIN
DECLARE rowno INTEGER;
DECLARE isdone boolean;
DECLARE privacy INTEGER;
SELECT COUNT(*), TASK.isdone, TASK.privacy
into @rowno, @isdone, @privacy
FROM TASK
WHERE TASK.tid = mytid;
IF @rowno = 0 THEN
  /* NO RECORD EXISTS */
  SELECT (-1) AS status;
ELSE
  IF @privacy <> 0 THEN
    /* IF ALREADY PUBLISHED */
    IF(myisdone <> @isdone) AND (myisdone = FALSE) THEN
      /* NOT ALLOWED WHEN PUBLISHED */
      SELECT (-1) AS status;
    ELSE
      UPDATE TASK
      SET TASK.isdone = myisdone
      WHERE TASK.tid = mytid;
      SELECT (1) AS status;
    END IF;
  ELSE
    /* IS PRIVATE, OK TO MODIFY */
    UPDATE TASK
    SET TASK.isdone = myisdone
    WHERE TASK.tid = mytid;
    SELECT (1) AS status;
  END IF;
END IF;
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
COUNT(EXP.expid) AS expcount, TASK.tstamp, TASK.isdone,utg.t_order,
utg.tgid, utg.priority, utg.title, utg.exp, utg.avatar, utg.type, TASK.privacy, 
CONCAT(CONCAT(IFNULL(TASK.tid, 'NULL'), ' '),utg.tgid) AS pk
FROM
(
  SELECT T_GROUP.tgid, T_GROUP.priority, T_GROUP.type,
  T_GROUP.title, IFNULL(T_GROUP.t_order,"") AS t_order,USER.uid, USER.username,
  USER.firstname, USER.lastname, USER.email, USER.exp, USER.avatar
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
ORDER BY priority DESC,tgid DESC, tstamp DESC;
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
COUNT(EXP.expid) AS expcount, TASK.tstamp, TASK.isdone, TASK.privacy
FROM TASK LEFT JOIN USER
ON TASK.uid = USER.uid
LEFT JOIN EXP
ON TASK.tid = EXP.tid
WHERE TASK.tid = mytid
GROUP BY EXP.tid;
END // 
DELIMITER ;

/* GET ALL PEOPLE I FOLLOW */
DELIMITER // 
CREATE PROCEDURE GetMyFollows(
IN myuid int
) 
BEGIN
SELECT myuid AS uid, ft.fuid,
USER.exp, USER.username,
USER.firstname,
USER.lastname, USER.email,
USER.avatar
FROM
(
  SELECT *
  FROM FRIEND
  WHERE uid = myuid
  AND frid NOT IN
  (
    SELECT FRIEND1.frid
    FROM 
    (SELECT * FROM FRIEND WHERE uid = myuid ) FRIEND1
    JOIN 
    (SELECT * FROM FRIEND WHERE fuid = myuid ) FRIEND2
    ON
    FRIEND1.fuid = FRIEND2.uid
  )
) ft
LEFT JOIN USER
ON USER.uid = ft.fuid ORDER BY USER.firstname;
END // 
DELIMITER ;

/* GET ALL MY FANS */
DELIMITER // 
CREATE PROCEDURE GetMyFans(
IN myuid int
)
BEGIN
SELECT ft.uid, myuid AS fuid,
USER.exp, USER.username,
USER.firstname,
USER.lastname, USER.email,
USER.avatar
FROM
(
  SELECT *
  FROM FRIEND
  WHERE fuid = myuid
  AND frid NOT IN
  (
    SELECT FRIEND2.frid
    FROM 
    (SELECT * FROM FRIEND WHERE uid = myuid ) FRIEND1
    JOIN 
    (SELECT * FROM FRIEND WHERE fuid = myuid ) FRIEND2
    ON
    FRIEND1.fuid = FRIEND2.uid
  )
) ft
LEFT JOIN USER
ON USER.uid = ft.uid ORDER BY USER.firstname;
END // 
DELIMITER ;

/* GET ALL MY FRIENDS */
DELIMITER // 
CREATE PROCEDURE GetMyFriends(
IN myuid int
)
BEGIN
SELECT myuid AS uid, ft.fuid,
USER.exp, USER.username,
USER.firstname,
USER.lastname, USER.email,
USER.avatar
FROM
(
  SELECT FRIEND1.uid, FRIEND1.fuid
  FROM 
  (SELECT * FROM FRIEND WHERE uid = myuid ) FRIEND1
  JOIN 
  (SELECT * FROM FRIEND WHERE fuid = myuid ) FRIEND2
  ON
  FRIEND1.fuid = FRIEND2.uid
) ft
LEFT JOIN USER
ON USER.uid = ft.fuid ORDER BY USER.firstname;
END // 
DELIMITER ;


/* ADD A FRIEND */
DELIMITER // 
CREATE PROCEDURE AddFriend(
IN myuid int,
IN myfuid int
) 
BEGIN
DECLARE rowno INTEGER;
SELECT COUNT(*)
INTO @rowno
FROM FRIEND f
WHERE f.uid = myuid AND f.fuid = myfuid;
IF @rowno <> 0 THEN
    SELECT (-1) AS status;
ELSE
    INSERT INTO FRIEND (uid, fuid)
    VALUES(myuid, myfuid);
    SELECT (1) AS status;
END IF;
END // 
DELIMITER ;

/* UNFOLLOW I.E DELETE A FRIEND */
DELIMITER // 
CREATE PROCEDURE UnfollowFriend(
IN myuid int,
IN myfuid int
) 
BEGIN 
DELETE FROM FRIEND
WHERE FRIEND.uid = myuid AND FRIEND.fuid = myfuid;
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* SEARCH FRIENDS */
DELIMITER // 
CREATE PROCEDURE SearchUsers(
IN myuid int,
IN myinput VARCHAR(50) CHARACTER SET utf8
) 
BEGIN
SELECT uid,
exp, username,
firstname,
lastname, email, 
avatar,fuid
FROM USER u 
LEFT JOIN (SELECT fuid FROM FRIEND WHERE uid = myuid) f ON u.uid = f.fuid
WHERE uid <> myuid AND (LOWER(username) LIKE CONCAT('%', LOWER(myinput), '%')
OR LOWER(firstname) LIKE CONCAT('%', LOWER(myinput), '%')
OR LOWER(lastname) LIKE CONCAT('%', LOWER(myinput), '%'));
END // 
DELIMITER ;

/* UPDATE AVATAR */
DELIMITER // 
CREATE PROCEDURE UpdateAvatar(
IN myuid int,
IN myavatar int
) 
BEGIN
UPDATE USER
SET avatar = myavatar
WHERE uid = myuid;
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* GET ALL FRIEND'S TASKS */
DELIMITER // 
CREATE PROCEDURE GetAllFriendTasks(
IN myfuid int
) 
BEGIN
SELECT TASK.tid, TASK.otid, utg.uid, utg.username,
utg.firstname, utg.lastname, utg.email, TASK.content,
COUNT(EXP.expid) AS expcount, TASK.tstamp, TASK.isdone,utg.t_order,
utg.tgid, utg.priority, utg.title, utg.exp, utg.avatar, utg.type, TASK.privacy, 
CONCAT(CONCAT(IFNULL(TASK.tid, 'NULL'), ' '),utg.tgid) AS pk
FROM
(
  SELECT T_GROUP.tgid, T_GROUP.priority, T_GROUP.type,
  T_GROUP.title, IFNULL(T_GROUP.t_order,"") AS t_order,USER.uid, USER.username,
  USER.firstname, USER.lastname, USER.email, USER.exp, USER.avatar
  FROM T_GROUP RIGHT JOIN USER
  ON T_GROUP.uid = USER.uid
  WHERE USER.uid = myfuid
) utg
LEFT JOIN TASK
ON
TASK.tgid = utg.tgid
AND TASK.privacy > 0
LEFT JOIN EXP
ON TASK.tid = EXP.tid
GROUP BY pk
ORDER BY priority DESC,tgid DESC, tstamp DESC;
END // 
DELIMITER ;