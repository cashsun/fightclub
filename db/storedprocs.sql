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
DROP PROCEDURE IF EXISTS GetFriends;
DROP PROCEDURE IF EXISTS GetMyFollows;
DROP PROCEDURE IF EXISTS GetMyFans;
DROP PROCEDURE IF EXISTS GetMyFriends;
DROP PROCEDURE IF EXISTS GetUserFollows;
DROP PROCEDURE IF EXISTS AddFriend;
DROP PROCEDURE IF EXISTS UnfollowFriend;
DROP PROCEDURE IF EXISTS SearchUsers;
DROP PROCEDURE IF EXISTS UpdateAvatar;
DROP PROCEDURE IF EXISTS GetAllFriendTasks;
DROP PROCEDURE IF EXISTS Fighto;
DROP PROCEDURE IF EXISTS GetUserTasks;
DROP PROCEDURE IF EXISTS GetTexpByTgid;
DROP PROCEDURE IF EXISTS CreateComment;
DROP PROCEDURE IF EXISTS DeleteComment;
DROP PROCEDURE IF EXISTS GetComments;
DROP PROCEDURE IF EXISTS GetFightoList;
DROP PROCEDURE IF EXISTS GetNews;

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
DECLARE existU BOOLEAN;
DECLARE existM BOOLEAN;
DECLARE uid INTEGER;
DECLARE tgid INTEGER;

SET time_zone = "+00:00";

SELECT (COUNT(*)>0) INTO @existU
FROM USER WHERE username = myusername;
SELECT (COUNT(*)>0) INTO @existM
FROM USER WHERE email = myemail;
IF (@existM = TRUE OR @existU =TRUE) THEN
  SELECT 0 AS uid, @existM AS existM, @existU as existU;
ELSE
  INSERT INTO USER (username, passwd, firstname, lastname, email, avatar)
  VALUES(myusername, mypasswd, myfirstname, mylastname, myemail, myavatar);
  SELECT LAST_INSERT_ID() AS uid into @uid;
  INSERT INTO T_GROUP (uid, title, priority, type) 
    VALUES(@uid, 'My First Task Group', 0, 0);
  SELECT LAST_INSERT_ID() AS tgid into @tgid;
  INSERT INTO TASK(uid, tgid, content)
    VALUES(@uid, @tgid, 'Buy some milk');
  SELECT @uid as uid;
END IF;
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
WHERE ((username = myusername AND passwd = mypasswd)
OR (email = myusername AND passwd = mypasswd));
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
SET time_zone = "+00:00";
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
SET time_zone = "+00:00";
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
IN myprivacy int,
IN mydeadline TIMESTAMP
) 
BEGIN
UPDATE TASK
SET TASK.content = mycontent,
TASK.privacy = myprivacy,
TASK.lastupdate = now(),
TASK.deadline = mydeadline
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
      SET TASK.isdone = myisdone,
      TASK.lastupdate = now()
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
SET time_zone = "+00:00";
SELECT TASK.tid, TASK.otid, utg.uid, utg.username,
utg.firstname, utg.lastname, utg.email, TASK.content,
COUNT(EXP.expid) AS texp, TASK.tstamp, TASK.deadline, TASK.isdone,utg.t_order,
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
SET time_zone = "+00:00";
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
SELECT USER.uid, ft.fuid,
USER.exp, USER.username,
USER.firstname,
USER.lastname, USER.email,
USER.avatar
FROM
(
  SELECT *
  FROM FRIEND
  WHERE uid = myuid
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
SELECT ft.uid,
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
SELECT myuid AS fuid, ft.fuid AS uid,
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


/* GET ALL PEOPLE THE USER FOLLOW */
DELIMITER // 
CREATE PROCEDURE GetUserFollows(
IN myfuid int,
IN myuid int
) 
BEGIN
SELECT USER.uid, FRIEND.fuid,
USER.exp, USER.username,
USER.firstname,
USER.lastname, USER.email,
USER.avatar
FROM
(
  SELECT *
  FROM FRIEND
  WHERE uid = myfuid AND fuid!=myuid
) ft
LEFT JOIN USER
ON USER.uid = ft.fuid
LEFT JOIN FRIEND
ON USER.uid = FRIEND.fuid AND FRIEND.uid = myuid
ORDER BY USER.firstname;
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
DECLARE eventid INTEGER;
SELECT COUNT(*)
INTO @rowno
FROM FRIEND f
WHERE f.uid = myuid AND f.fuid = myfuid;
IF @rowno <> 0 THEN
    SELECT (-1) AS status;
ELSE
    INSERT INTO FRIEND (uid, fuid)
    VALUES(myuid, myfuid);
    SELECT COUNT(*), eventid 
    INTO @rowno, @eventid
    FROM EVENT
    WHERE EVENT.uid1 = myuid
    AND EVENT.uid2 = myfuid;
    IF @rowno > 0 THEN
      /* ALREADY EXISTS,UPDATE TIMESTAMP */
      UPDATE EVENT
      SET EVENT.tstamp = now()
      WHERE eventid = @eventid;
    ELSE
      INSERT INTO EVENT(eventtype, uid1, uid2)
      VALUES(3, myuid, myfuid);
    END IF;
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
WHERE uid <> myuid AND
(LOWER(CONCAT(firstname, ' ', lastname)) LIKE CONCAT('%', LOWER(myinput), '%')
OR LOWER(CONCAT(lastname, ' ', firstname)) LIKE CONCAT('%', LOWER(myinput), '%')
OR LOWER(username) LIKE CONCAT('%', LOWER(myinput), '%'));
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
CREATE PROCEDURE GetUserTasks(
IN myfuid int,
IN myuid int
) 
BEGIN
DECLARE privacylevel INTEGER;
DECLARE isfans BOOLEAN;
SET @privacylevel = 1;
SET time_zone = "+00:00";
SELECT (COUNT(*)>0) into @isfans FROM FRIEND
WHERE fuid = myuid AND uid = myfuid; 

IF(@isfans = TRUE) THEN
  SET @privacylevel = 0;
END IF;

SELECT utg.fuid, myfuid AS uid, TASK1.tid, TASK1.otid, utg.username,
utg.firstname, utg.lastname, utg.email, TASK1.content,
COUNT(EXP.expid) AS texp, TASK1.tstamp, TASK1.isdone,utg.t_order,
utg.tgid, utg.priority, utg.title, utg.exp, utg.avatar, utg.type, TASK1.privacy, 
CONCAT(CONCAT(IFNULL(TASK1.tid, 'NULL'), ' '),utg.tgid) AS pk, (EXP1.expid IS NOT NULL) AS isliked
FROM
(
  SELECT T_GROUP.tgid, T_GROUP.priority, T_GROUP.type,
  T_GROUP.title, IFNULL(T_GROUP.t_order,"") AS t_order, uf.username,
  uf.firstname, uf.lastname, uf.email, uf.exp, uf.avatar, uf.fuid
  FROM T_GROUP RIGHT JOIN 
  (
    SELECT * FROM
    USER
    LEFT JOIN
    (
      SELECT fuid FROM
      FRIEND
      WHERE uid = myuid AND fuid = myfuid
    )f
    ON USER.uid = f.fuid
    WHERE USER.uid = myfuid
  )uf
  ON T_GROUP.uid = uf.uid
) utg
LEFT JOIN
(
  SELECT * FROM
  TASK
  WHERE privacy > @privacylevel
  AND uid = myfuid
)TASK1
ON
TASK1.tgid = utg.tgid
LEFT JOIN EXP
ON TASK1.tid = EXP.tid
LEFT JOIN
EXP EXP1
ON TASK1.tid = EXP1.tid
AND
EXP1.uid = myuid
GROUP BY pk
ORDER BY priority DESC,tgid DESC, tstamp DESC;
END // 
DELIMITER ;

/* ADD 1 EXP TO A TASK */
DELIMITER // 
CREATE PROCEDURE Fighto(
IN myuid int,
IN mytid int
) 
BEGIN
DECLARE exist BOOLEAN;
DECLARE tuid INTEGER;
SET time_zone = "+00:00";
SELECT (COUNT(expid)>0) INTO @exist FROM
EXP WHERE uid = myuid
AND tid = mytid;
IF(@exist = FALSE) THEN
  INSERT INTO EXP (uid, tid)
  VALUES(myuid, mytid);
  SELECT uid INTO @tuid FROM TASK
  WHERE tid = mytid;
  INSERT INTO EVENT (eventtype, uid1, uid2, tid)
  VALUES(2, myuid, @tuid, mytid);
  SELECT 1 AS status;
ELSE
  SELECT -1 AS status;
END IF;
END // 
DELIMITER ;



/* CREATE COMMENT */
DELIMITER // 
CREATE PROCEDURE CreateComment(
IN myuid int,
IN mytid int,
IN mycontent char(140) CHARACTER SET utf8
) 
BEGIN
DECLARE cid INTEGER;
DECLARE tuid INTEGER;
DECLARE tid INTEGER;
DECLARE tgid INTEGER;
SET time_zone = "+00:00";
INSERT INTO COMMENT (uid, tid, content)
VALUES(myuid, mytid, mycontent);
SELECT LAST_INSERT_ID() INTO @cid;

SELECT COMMENT.uid, COMMENT.tid, 
TASK.tgid INTO @tuid,@tid,@tgid
FROM COMMENT LEFT JOIN TASK
ON COMMENT.tid = TASK.tid
WHERE COMMENT.commentid = @cid; 

INSERT INTO EVENT(eventtype, uid1, uid2, cid, tid, tgid)
VALUES(0, myuid, @tuid, @cid, @tid, @tgid);
END // 
DELIMITER ;

/* DELETE COMMENT */
DELIMITER // 
CREATE PROCEDURE DeleteComment(
IN mycommentid int
) 
BEGIN
DELETE FROM COMMENT
WHERE commentid = mycommentid;
SELECT ROW_COUNT() AS rows_affected;
END // 
DELIMITER ;

/* GET COMMENTS */
DELIMITER // 
CREATE PROCEDURE GetComments(
IN mytid int,
IN mylastcid int
)
BEGIN
SET time_zone = "+00:00";
SELECT COMMENT.commentid, COMMENT.uid,
COMMENT.tid, COMMENT.content,
COMMENT.tstamp, USER.username,
USER.firstname, USER.lastname,
USER.email, USER.avatar
FROM COMMENT LEFT JOIN USER
ON COMMENT.uid = USER.uid
WHERE COMMENT.commentid > mylastcid AND tid = mytid
ORDER BY COMMENT.tstamp ASC LIMIT 10;
END // 
DELIMITER ;

/* GET FIGHTOLIST */
DELIMITER // 
CREATE PROCEDURE GetFightoList(
IN mytid int
)
BEGIN
SET time_zone = "+00:00";
SELECT EXP.expid,
USER.firstname, USER.lastname,
USER.username,
USER.email, USER.avatar, USER.uid
FROM EXP LEFT JOIN USER
ON EXP.uid = USER.uid
WHERE EXP.tid = mytid
ORDER BY EXP.expid DESC LIMIT 30;
END // 
DELIMITER ;


/* GET NEWS */
DELIMITER // 
CREATE PROCEDURE GetNews(
IN myuid int
)
BEGIN
SET time_zone = "+00:00";
/* STEP GET ALL COMMENT */
SELECT u1.uid AS uid1,u1.firstname AS firstname1, 
u1.lastname AS lastname1,
u1.avatar AS avatar1, u1.username AS username1,
u2.uid AS uid2, u2.username AS username2,
u2.firstname AS firstname2, 
u2.lastname AS lastname2,
u2.avatar AS avatar2, TASK.tid, TASK.content AS tcontent,
TASK.isdone, TASK.privacy, TASK.deadline, EVENT.tstamp,
COMMENT.content AS ccontent, EVENT.cid, EVENT.eventtype,
T_GROUP.title,T_GROUP.type, EXP.expid, e2.expid IS NOT NULL AS liked
FROM EVENT
LEFT JOIN USER u1 ON EVENT.uid1 = u1.uid
LEFT JOIN USER u2 ON EVENT.uid2 = u2.uid
LEFT JOIN TASK ON EVENT.tid = TASK.tid
LEFT JOIN COMMENT ON EVENT.cid = COMMENT.commentid
LEFT JOIN T_GROUP ON T_GROUP.tgid = EVENT.tgid
LEFT JOIN EXP ON EVENT.tid = EXP.tid 
AND (EVENT.uid1 = EXP.uid OR EVENT.uid2 = EXP.uid)
LEFT JOIN EXP e2 ON TASK.tid = e2.tid
AND myuid = e2.uid
WHERE EVENT.uid1 = myuid OR EVENT.uid2 = myuid;
END // 
DELIMITER ;