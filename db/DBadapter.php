<?php        class DBadapter{                private $con = null;                private $host = 'localhost';                private $user = 'kilima5';                private $pass = 'dss871220';                function connect(){            $this->con = mysql_connect($this->host,$this->user,$this->pass) or die(mysql_error());            mysql_set_charset('utf8', $this->con) or die('cannot set names to utf8');                        mysql_select_db("fightdb",$this->con) or die(mysql_error());        }                function disconnect(){                        mysql_close($this->con);                }                function last_insert_id(){            $result = mysql_query("SELECT LAST_INSERT_ID() AS id") or die(mysql_error());            $row = mysql_fetch_assoc($result);            $id = $row['id'];            return $id;        }                        function register($username, $passwd, $firstname, $lastname, $email, $avatar){            $this->connect();            $query = sprintf("CALL CreateUser('%s', '%s', '%s', '%s', '%s', %s)",                              mysql_real_escape_string($username),                              md5($passwd),                              mysql_real_escape_string($firstname),                              mysql_real_escape_string($lastname),                              mysql_real_escape_string($email),                              mysql_real_escape_string($avatar));            if(mysql_query($query) or die(mysql_error()))            {              /* if success */              $uid = $this->last_insert_id();              /* create default task group & task */              $tgid = $this->CreateTaskGroup($uid, 'My first Group', 0, 0);              $this->CreateOriTask($uid, $tgid, 'Say Hi to FightClub!');              return $uid;            }            else              return -1;          }                function login($username, $passwd){            $this->connect();            $query = sprintf("CALL ValidateUser('%s', '%s')",                              mysql_real_escape_string($username),                              md5($passwd));            $result = mysql_query($query) or die(mysql_error());            if($row = mysql_fetch_assoc($result))              /* found entry */              return array('data'=>$row, 'valid' => true);            else              return array('valid' => false);        }                function getUser($uid){            $this->connect();            $query = sprintf("CALL GetUser('%s')",                              mysql_real_escape_string($uid));            $result = mysql_query($query) or die(mysql_error());            if($row = mysql_fetch_assoc($result))              /* found entry */              return array('data'=>$row, 'valid' => true);            else              return array('valid' => false);        }                function getTask($tid, $isOt){                        $this->connect();            if($isOt)            {              $dbtask = mysql_query("CALL GetTask($tid)") or die(mysql_error());              while ($dbtask_row = mysql_fetch_array($dbtask)){                  $result = $dbtask_row;                  $result['exp'] = $dbtask_row['expcount'];                  $result['date'] = $dbtask_row['ts'];                  $result['creatorname'] = $dbtask_row['firstname']." ".$dbtask_row['lastname'];                                }             }            else            {                          }                            return $result;                }        /*        function getAllOriByUid($uid){            $results = mysql_query("CALL GetAllMyOriTasks($uid)") or die(mysql_error());            return $results;        }                function getAllRepoByUid($uid){            $results = mysql_query("CALL GetAllMyRepoTasks($uid)") or die(mysql_error());            return $results;        }        */        function getAllByUid($uid){            $query = sprintf("CALL GetAllMyTasks(%s)", mysql_real_escape_string($uid));            $results = mysql_query($query) or die(mysql_error());            return $results;        }                function getFriends($uid){                        $this->connect();            $query = sprintf("CALL GetFriends(%s)", mysql_real_escape_string($uid));            $results = mysql_query($query) or die(mysql_error());            $friends = array();            $counter = 0;            while($row = mysql_fetch_assoc($results))            {              $friends[$counter] = $row;              $counter++;            }            return $friends;        }        /*        function getTasksByGroup($uid, $tgid){                        $this->connect();                        $dbtask = mysql_query("CALL ListOriTask($uid,$tgid)") or die(mysql_error());            $taskCounter = 0;            $results = Array();            while ($dbtask_row = mysql_fetch_array($dbtask)){                $result_row = array(                  "tid" => $dbtask_row['tid'],                  "creatorId" => $dbtask_row['uid'],                  "content" => $dbtask_row['content'],                  "exp" => $dbtask_row['expcount'],                  "date" => $dbtask_row['ts'],                  "isdone" => $dbtask_row['isdone'],                  "creatorname" => ($dbtask_row['firstname']." ".$dbtask_row['lastname']),                );                $results[$taskCounter] = $result_row;                $taskCounter++;            }                                     return $results;                }        */        function createTaskGroup($uid, $title, $priority, $type){                        $this->connect();            $query = sprintf("CALL CreateTaskGroup(%s, '%s', %s, %s, '%s')",                                  mysql_real_escape_string($uid),                                  mysql_real_escape_string($title),                                  mysql_real_escape_string($priority),                                  mysql_real_escape_string($type),                                  "");            if(mysql_query($query) or die(mysql_error()))              return $this->last_insert_id();            else              return -1;          }                function updateTaskGroup($tgid, $title, $priority, $type){                        $this->connect();            $query = sprintf("CALL UpdateTaskGroup(%s,'%s',%s,%s,'%s')",                                mysql_real_escape_string($tgid),                                mysql_real_escape_string($title),                                mysql_real_escape_string($priority),                                mysql_real_escape_string($type),                                "");            if($result = mysql_query($query) or die(mysql_error()))            {              $row = mysql_fetch_assoc($result);              return $row['rows_affected'];            }            else              return -1;        }                function updateTaskGroupTaskOrder($tgid, $t_order){                        $this->connect();            $query = sprintf("CALL UpdateTaskGroupOrder(%s,'%s')",                                mysql_real_escape_string($tgid),                                mysql_real_escape_string($t_order));            $result = mysql_query($query) or die(mysql_error());                   if($result = mysql_query($query) or die(mysql_error()))            {              $row = mysql_fetch_assoc($result);              return $row['rows_affected'];            }            else              return -1;               }                 function deleteTaskGroup($tgid){                        $this->connect();            $query = sprintf("CALL DeleteTaskGroup(%s)", mysql_real_escape_string($tgid));            $result = mysql_query($query) or die(mysql_error());                   if($result = mysql_query($query) or die(mysql_error()))            {              $row = mysql_fetch_assoc($result);              return $row['rows_affected'];            }            else              return -1;               }                 function createOriTask($uid, $tgid, $content){                        $this->connect();            $query = sprintf("CALL CreateTask(%s, 0, %s, '%s')",                                  mysql_real_escape_string($uid),                                  mysql_real_escape_string($tgid),                                  mysql_real_escape_string($content));            if(mysql_query($query) or die(mysql_error()))              return $this->last_insert_id();            else              return -1;        }                function createRepoTask($uid, $otid, $tgid, $content){                        $this->connect();            $query = sprintf("CALL CreateTask(%s, %s, %s, '%s')",                                  mysql_real_escape_string($uid),                                  mysql_real_escape_string($otid),                                  mysql_real_escape_string($tgid),                                  mysql_real_escape_string($content));            if(mysql_query($query) or die(mysql_error()))              return $this->last_insert_id();            else              return -1;        }                function updateTask($tid, $content, $privacy){                        $this->connect();            $query = sprintf("CALL UpdateTask(%s, '%s', %s)",                                mysql_real_escape_string($tid),                                mysql_real_escape_string($content),                                mysql_real_escape_string($privacy));            $result = mysql_query($query) or die(mysql_error());                   if($result = mysql_query($query) or die(mysql_error()))            {              $row = mysql_fetch_assoc($result);              return $row['rows_affected'];            }            else              return -1;               }                function toggleTaskComplete($tid, $isdone){                        $this->connect();            $query = sprintf("CALL toogleTaskComplete(%s, %s)",                                mysql_real_escape_string($tid),                                mysql_real_escape_string($isdone));            if( $result = mysql_query($query) or die(mysql_error()))            {              $row = mysql_fetch_assoc($result);              return $row['status'];            }            else              return -1;                }                function deleteTask($tid){                        $this->connect();            $query = sprintf("CALL DeleteTask(%s)", mysql_real_escape_string($tid));            $result = mysql_query($query) or die(mysql_error());                   if($result = mysql_query($query) or die(mysql_error()))            {              $row = mysql_fetch_assoc($result);              return $row['rows_affected'];            }            else              return -1;               }                    }?>