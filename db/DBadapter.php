<?php        class DBadapter{                private $con = null;                private $host = 'localhost';                private $user = 'kilima5';                private $pass = 'dss871220';                function connect(){            $this->con = mysql_connect($this->host,$this->user,$this->pass) or die(mysql_error());                        mysql_set_charset('utf8', $this->con) or die('cannot set names to utf8');                        mysql_select_db("fightbd",$this->con) or die(mysql_error());                }                function disconnect(){                        mysql_close($this->con);                }                   function login($username, $password){            return true;        }                function getTask($uid, $tgid){                        $this->connect();                        $dbtask = mysql_query("CALL ListOriTask") or die(mysql_error());            $taskCounter = 0;            $results = new Array();            while ($dbtask_row = mysql_fetch_array($dbtask)){                $result_row = array(                  "tid" => dbtask_row['tid'],                  "creatorId" => dbtask_row['uid'],                  "content" => dbtask_row['content'],                  "exp" => dbtask_row['expcount'],                  "date" => dbtask_row['ts'],                  "isDone" => dbtask_row['isDone'],                  "creatorName" => (dbtask_row['firstname']." ".dbtask_row['lastname']),                );                $results[$taskCounter] = $result_row;            }                                    $this->disconnect();                return $results;                }                        function createTask($uid, $tgid, $content){                        $this->connect();                        $dbtask = mysql_query("CALL CreateOriTask") or die(mysql_error());                $this->disconnect();                return mysql_insert_id();                }           //        function gettitleTable(){            //            $this->connect();            //            $titleTable = mysql_query("SELECT title FROM posts ORDER BY id DESC") or die(mysql_error());            //            $titleCounter = 0;            //            while ($titleTable_row = mysql_fetch_array($titleTable)){                //                $titles[$titleCounter] = $titleTable_row['title'];                //                $titleCounter++;            //            }                        //            $this->disconnect();            //            return $titles;        ////        }                //        function getPosts(){            //            $this->connect();            //            $ids = null;            //            $idsTable = mysql_query("SELECT id FROM posts ORDER BY id DESC") or die(mysql_error());            //            $idsCounter = 0;            //            while ($idsTable_row = mysql_fetch_array($idsTable)){                //                $ids[$idsCounter] = $idsTable_row['id'].'.php';                //                $idsCounter++;            //            }                        //            $this->disconnect();            //            return $ids;        ////        }//        //        function getComments($postid){//            $this->connect();            //            $commentEntities = null;            //            $postid=mysql_real_escape_string($postid);//            $commentTable = mysql_query("SELECT name,pic,comment,date FROM comments WHERE postid=$postid ORDER BY date DESC;") or die(mysql_error());            //            $idsCounter = 0;            //            while ($idsTable_row = mysql_fetch_array($commentTable)){                //                $commentEntities[$idsCounter]['name'] = $idsTable_row['name'];//                $commentEntities[$idsCounter]['pic'] = $idsTable_row['pic'];//                $commentEntities[$idsCounter]['comment'] = $idsTable_row['comment'];//                $commentEntities[$idsCounter]['date'] = $idsTable_row['date'];//                $idsCounter++;            //            }                        //            $this->disconnect();            //            return $commentEntities;   //            //        }//        function insertComment($params){//            $this->connect(); //            $postid = mysql_real_escape_string($params['postid']);//            $name = mysql_real_escape_string($params['name']);//            $comment = mysql_real_escape_string($params['comment']);//            mysql_query("INSERT INTO `comments` (`postid`,  `name`, `comment`,`date`) VALUES ('$postid', '$name', '$comment', CURRENT_TIMESTAMP);")or die(mysql_error());//            $this->disconnect();      //        }            }?>