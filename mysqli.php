<?php
/*
  封装 连接数据库和数据库 增、删、改、查 方法
  
*/

/**
 * con [连接数据库]
 * [字符串] $name [数据库服务器登录用户名]
 * [字符串] $psd [数据库服务器登录密码]
 * [字符串] $db [数据库名]
 * [字符串] $host [数据库服务器地址]
 * [字符串] $code [数据传输编码]
 */
function con($name,$pwd,$db,$host='localhost',$code='utf8'){
	$_SESSION['mysqli']=mysqli_connect($host,$name,$pwd,$db) or die('连接数据库失败'); //连接数据库服务器并选择数据库
	mysqli_query($_SESSION['mysqli'],'set names '.$code); //设置数据传输编码
} 
con('root','root','wh');


//insert into admin(username,password) values('admin','123456');
/*
  add mysqli 增加数据
  [字符串]$table[数据库表名]
  [数组]$data[数组的键对应数据表的字段名，数组的值对应字段的值]
  
*/
function add($table,$data){
	$field ='';
	$values = '';
  if(!is_array($data)){
		return false;
	}
	foreach ($data as $key => $value) {
		$field .= $key.',';
		$values.="'".$value."',";
	}
	$field = rtrim($field,',');
	$values = rtrim($values,',');
	$sql = "insert into $table ($field) values ($values)";
	$result = mysqli_query($_SESSION['mysqli'],$sql);
	if($result){
		return mysqli_insert_id($_SESSION['mysqli']);
	}
	else{
		return false;
	}

}
// $r=add('admin',array('username'=>'admin','password'=>'123456'));
// var_dump($r);

//delete from admin where id=1;
/*
  del mysqli 删除数据
  [字符串]$table[数据库表名]
  [字符串]$where[条件]
*/
function del($table,$where){
	$sql = "delete from $table where $where"; 
	return mysqli_query($_SESSION['mysqli'],$sql);   
}
//$a=del('admin','id=3');

//update admin set username = '逗比杨广献'，password='00000' where id=4;
/*
  edit mysqli 修改数据
  [字符串]$table[数据库表名]
  [数组]$data[数组的键表示数据库表名字段，数组的值表示数据库表名字段的值]
  [字符串]$where[条件]
*/
function edit($table,$data,$where){
  $set ='';
  if(!is_array($data)){
  	return false;
  }
  foreach ($data as $key => $value) {
    $set .=$key."='".$value."',";   
  }
  $set = rtrim($set,',');
  $sql = "update $table set $set where $where";
  return mysqli_query($_SESSION['mysqli'],$sql);
}
// $e=edit('admin',array('username'=>'逗比杨广献','password'=>'000000'),'id=4');
// var_dump($e);


// 查询一条 select * from admin where id = 4;
/*
  getOne mysqil 数据库查询一条数据
  [字符串]$table[数据库表名]
  [字符串]$where[条件]
  [字符串]$field[要查询的字段名]
*/


function getOne($table,$where,$field='*'){
	$sql = "select $field from $table where $where";
	$result = mysqli_query($_SESSION['mysqli'],$sql);
    if(!$result){
    	return false;
    }
    return mysqli_fetch_assoc($result);
}
// $s = getOne('admin','id=4');
// var_dump($s);

//查询多条 select * from newst9 where id>3 order by id desc limit 0,5;
/*
  getList mysqil 数据库查询多条数据
  [字符串]$table[数据库表名]
  [字符串]$where[条件]
  [字符串]$field[要查询的字段名]
  [字符串]$order[排序]
  [字符串]$limit[截取数据]
  
*/
function getList($table,$where='',$order='',$limit='',$field='*'){
  $sql="select $field from $table";
  if($where!='')
  {
    $sql.=" where $where";
  }
  if($order!='')
  {
    $sql.=" order by $order";
  }
  if($limit!='')
  {
    $sql.=" limit $limit";
  }
  $result=mysqli_query($_SESSION['mysqli'],$sql);
  if($result->num_rows==0){
    return false;
  }
  while ( $row=mysqli_fetch_assoc($result) ) {
    $data[]=$row;
  }
  return $data;
}


// $g=getList('news','id>3','id desc','0,5');
// var_dump($g);

//select 表1.字段,表2.字段... from 表1 inner[left|right] join 表2[,表3...] on 表1.某字段=表2.某字段[,表1.某字段=表3.某字段...] where 表1.id>10 order by 表1.id desc limit 2,5;
/*
  getJoin 联表查询数据
  [字符串]$table1,$table2[数据库表名]
  [字符串]$field[要查询的字段名]
  [字符串]$on[关联条件]
  [字符串]$where[条件]
  [字符串]$order[排序]
  [字符串]$limit[截取数据]
  [字符串]$join[关联类型，默认内联]

*/
function getJoin($table1,$table2,$field,$on,$where='',$order='',$limit='',$join='inner'){
    $sql = "select $field from $table1 $join join $table2 on $on";
    if($where != ''){
    	$sql .= "where $where";
    }
    if($order != ''){
    	$sql .="order by $order";
    }
    if($limit != ''){
    	$sql .="limit $limit";
    }
     echo $sql;
    $result = mysqli_query($_SESSION['mysqli'],$sql);
    if($result->num_rows == 0){
    	return false;
    }
    while($row = mysqli_fetch_assoc($result)){
    	$data[] = $row;
    }
    return $data;

}
$result = getJoin('admin',' level',' admin.*,level.name',' admin.level = level.id ','',' id desc');

// $g=getJoin('news','category','news.title,category.name','news.category_id=category.id');
// var_dump($g);

//select count(*) as count from news where id>5;
/*
  getCount 获取数据库统计数据
  [字符串]$where[条件]
  [字符串]$name[设置计数器名字，默认count]
  [字符串]$table[数据库表名] 
*/
function getCount($table,$where,$name='count',$filed='*'){
	$sql ="select count($filed) as $name from $table";
	if($where != ''){
		$sql .= " where $where";
	}
	// echo $sql;
	$result = mysqli_query($_SESSION['mysqli'],$sql);
	if(!$result){
		return false;
	}
    return mysqli_fetch_assoc($result);
}
// $g=getCount('news','id>40');
// var_dump($g);

/*
  cateOffspring获取所有后代分类
*/
 function cateOffspring(){
    

 } 
?>