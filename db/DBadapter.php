<?php        class DBadapter{                private $con = null;                private $host = 'localhost';                private $user = 'kilima5';                private $pass = 'dss871220';                function connect(){            $this->con = mysql_connect($this->host,$this->user,$this->pass) or die(mysql_error());                        mysql_set_charset('utf8', $this->con) or die('cannot set names to utf8');                        mysql_select_db("fightdb",$this->con) or die(mysql_error());                }                function disconnect(){                        mysql_close($this->con);                }                   function login($username, $password){            return true;        }                function getTask($tid, $isOt){                        $this->connect();                                    if($isOt)            {              $dbtask = mysql_query("CALL GetOriTask($tid)") or die(mysql_error());              while ($dbtask_row = mysql_fetch_array($dbtask)){                  $result = $dbtask_row;                  $result['exp'] = $dbtask_row['expcount'];                  $result['date'] = $dbtask_row['ts'];                  $result['creatorname'] = $dbtask_row['firstname']." ".$dbtask_row['lastname'];                                }             }            else            {              //$dbtask = mysql_query("CALL GetRepoTask") or die(mysql_error());            }                           $this->disconnect();                return $result;                }                function getAllOriByUid($uid){            $results = mysql_query("CALL GetAllMyOriTasks($uid)") or die(mysql_error());            return $results;        }                function getAllRepoByUid($uid){            $results = mysql_query("CALL GetAllMyRepoTasks($uid)") or die(mysql_error());            return $results;        }                function getAllByUid($uid){            $results = mysql_query("CALL GetAllMyTasks($uid)") or die(mysql_error());            return $results;        }                function getTasksByGroup($uid, $tgid){                        $this->connect();                        $dbtask = mysql_query("CALL ListOriTask($uid,$tgid)") or die(mysql_error());            $taskCounter = 0;            $results = Array();            while ($dbtask_row = mysql_fetch_array($dbtask)){                $result_row = array(                  "tid" => $dbtask_row['tid'],                  "creatorId" => $dbtask_row['uid'],                  "content" => $dbtask_row['content'],                  "exp" => $dbtask_row['expcount'],                  "date" => $dbtask_row['ts'],                  "isdone" => $dbtask_row['isdone'],                  "creatorname" => ($dbtask_row['firstname']." ".$dbtask_row['lastname']),                );                $results[$taskCounter] = $result_row;                $taskCounter++;            }                                    $this->disconnect();                return $results;                }                function createTaskGroup($uid, $title){                        $this->connect();                        $dbtask = mysql_query("CALL CreateTaskGroup($uid,$title)") or die(mysql_error());                $this->disconnect();                return mysql_insert_id();                }        function DeleteTaskGroup($tgid){                        $this->connect();                        $dbtask = mysql_query("CALL DeleteTaskGroup($tgid)") or die(mysql_error());                $this->disconnect();                return (mysql_affected_rows()>0)? TRUE : FALSE;                }                 function createOriTask($uid, $tgid, $content){                        $this->connect();                        $dbtask = mysql_query("CALL CreateOriTask($uid,$tgid,$content)") or die(mysql_error());                $this->disconnect();                return mysql_insert_id();                }        function DeleteOriTask($tid){                        $this->connect();                        $dbtask = mysql_query("CALL DeleteOriTask($tid)") or die(mysql_error());                $this->disconnect();                return (mysql_affected_rows()>0)? TRUE : FALSE;                }                          function createRepoTask($uid, $tgid, $otid){                        $this->connect();                        $dbtask = mysql_query("CALL CreateRepoTask($uid,$tgid,$otid)") or die(mysql_error());                $this->disconnect();                return mysql_insert_id();                }        function DeleteRepoTask($rtid){                        $this->connect();                        $dbtask = mysql_query("CALL DeleteOriTask($rtid)") or die(mysql_error());                $this->disconnect();                return (mysql_affected_rows()>0)? TRUE : FALSE;                }  //        function gettitleTable(){            //            $this->connect();            //            $titleTable = mysql_query("SELECT title FROM posts ORDER BY id DESC") or die(mysql_error());            //            $titleCounter = 0;            //            while ($titleTable_row = mysql_fetch_array($titleTable)){                //                $titles[$titleCounter] = $titleTable_row['title'];                //                $titleCounter++;            //            }                        //            $this->disconnect();            //            return $titles;        ////        }                //        function getPosts(){            //            $this->connect();            //            $ids = null;            //            $idsTable = mysql_query("SELECT id FROM posts ORDER BY id DESC") or die(mysql_error());            //            $idsCounter = 0;            //            while ($idsTable_row = mysql_fetch_array($idsTable)){                //                $ids[$idsCounter] = $idsTable_row['id'].'.php';                //                $idsCounter++;            //            }                        //            $this->disconnect();            //            return $ids;        ////        }//        //        function getComments($postid){//            $this->connect();            //            $commentEntities = null;            //            $postid=mysql_real_escape_string($postid);//            $commentTable = mysql_query("SELECT name,pic,comment,date FROM comments WHERE postid=$postid ORDER BY date DESC;") or die(mysql_error());            //            $idsCounter = 0;            //            while ($idsTable_row = mysql_fetch_array($commentTable)){                //                $commentEntities[$idsCounter]['name'] = $idsTable_row['name'];//                $commentEntities[$idsCounter]['pic'] = $idsTable_row['pic'];//                $commentEntities[$idsCounter]['comment'] = $idsTable_row['comment'];//                $commentEntities[$idsCounter]['date'] = $idsTable_row['date'];//                $idsCounter++;            //            }                        //            $this->disconnect();            //            return $commentEntities;   //            //        }//        function insertComment($params){//            $this->connect(); //            $postid = mysql_real_escape_string($params['postid']);//            $name = mysql_real_escape_string($params['name']);//            $comment = mysql_real_escape_string($params['comment']);//            mysql_query("INSERT INTO `comments` (`postid`,  `name`, `comment`,`date`) VALUES ('$postid', '$name', '$comment', CURRENT_TIMESTAMP);")or die(mysql_error());//            $this->disconnect();      //        }            }?>