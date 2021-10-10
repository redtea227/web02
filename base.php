<?php 
session_start();
date_default_timezone_set("Asia/Taipei");

class DB{
  private $dsn="mysql:host=localhost;charset=utf8;dbname=db55";
  private $root='root';
  private $password='12345';
  private $table;
  private $pdo;

  public function __construct($table){
    $this->table=$table;
    $this->pdo=new PDO($this->dsn,$this->root,$this->password);
  }

  public function all(...$arg){
    $sql="select * from $this->table ";
    if(isset($arg[0])){
      if(is_array($arg[0])){
        foreach($arg[0] as $key => $value){
          $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where ".implode(" && ",$tmp);
      }else{
        $sql=$sql.$arg[0];
      }
      if(isset($arg[1])){
        $sql=$sql.$arg[1];
      }
    }
    return $this->pdo->query($sql)->fetchAll();
  }

  public function count(...$arg){
    $sql="select count(*) from $this->table ";
    if(isset($arg[0])){
      if(is_array($arg[0])){
        foreach($arg[0] as $key => $value){
          $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where ".implode(" && ",$tmp);
      }else{
        $sql=$sql.$arg[0];
      }
      if(isset($arg[1])){
        $sql=$sql.$arg[1];
      }
    }
    return $this->pdo->query($sql)->fetchColumn();
  }

  public function find($id){
    $sql="select * from $this->table ";
      if(is_array($id)){
        foreach($id as $key => $value){
          $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where ".implode(" && ",$tmp);
      }else{
        $sql=$sql." where `id`='$id'";
      }
    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
  }

  public function del($id){
    $sql="delete from $this->table ";
      if(is_array($id)){
        foreach($id as $key => $value){
          $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        $sql=$sql." where ".implode(" && ",$tmp);
      }else{
        $sql=$sql." where `id`='$id'";
      }
    return $this->pdo->exec($sql);
  }
  
  public function sum($col){
    $sql="select sum(`$col`) from $this->table";

    return $this->pdo->query($sql)->fetchColumn();
  }

  public function save($array){
    if(isset($array['id'])){
      foreach($array as $key => $value){
        if($key!='id'){
          $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
      }
      $sql="update $this->table set ".implode(',',$tmp)." where `id`='{$array['id']}'";
    }else{
      $sql="insert into $this->table (`".implode("`,`",array_keys($array))."`) values ('".implode("','",$array)."')";
    }
    return $this->pdo->exec($sql);
  }
}

  $Total=new DB('total');
  $Mem=new DB('mem');
  $News=new DB('news');
  $Que=new DB('que');
  $Log=new DB('log');

  if($Total->count(['date'=>date("Y-m-d")])<=0){
    $Total->save(['date'=>date("Y-m-d"),'totla'=>0]);
  }

  if(!isset($_SESSION['total'])){
    $todayTotal=$Total->find(['date'=>date("Y-m-d")]);
    $todatTotal['total']++;
    $Total->save($todayTotal);
    $_SESSION['total']=1;
  }

  function to($url){
    header("location:".$url);
  }
?>