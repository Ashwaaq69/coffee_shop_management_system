<?php
include 'connection.php';

function read($table)
{
  global $conn;
  $result = $conn->query("select * from $table")->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function read_where($table, $condition)
{
  global $conn;
  $result = $conn->query("select * from $table where $condition ")->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function read_wheres($table, $condition)
{
    global $conn;
    
    // Ensure the condition is valid
    if (empty($condition)) {
        throw new Exception("Condition cannot be empty");
    }

    // Prepare the query for better security and to avoid errors
    $query = "SELECT * FROM $table WHERE $condition";
    $stmt = $conn->prepare($query);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function read_column($table,$column,  $id)
{ 
  global $conn;
  // $result = $conn->query("select $column from $table where ".$table."_id=$id")->fetchColumn();
  $result = $conn->query("select $column from $table where id=$id")->fetchColumn();
  return $result;
}

function insert($table, $data)
{
  global $conn;
  unset($data['id']);
  $keys = implode(",", array_keys($data));
  $namedKeys = ":" . implode(",:", array_keys($data));

  $sql = "insert into $table ( $keys ) values($namedKeys)";
  $stm = $conn->prepare($sql);
  $result = $stm->execute($data);
  return $result ? true : false;
}


// update function

function update($table, $data)
{
  global $conn;

  // "update tablename set column=:column,column1=:column1 ... where id = :id"
  $pairs = [];
  foreach (array_keys($data) as $k) {
    $pairs[] = $k . "=:" . $k;
  }
  $keyEqualColonKey = implode(',', $pairs);
  $sql = "update $table set $keyEqualColonKey where id=:id";
  $stm = $conn->prepare($sql);
  $result =  $stm->execute($data);
  return $result ? true : false;
}


// delete function

function delete($table, $id)
{
  global $conn;
  $sql = "delete from $table where id=:id";
  $stm = $conn->prepare($sql);
  $result =  $stm->execute(["id" => $id]);
  return $result ? true : false;
}



function escape($input)
{
  return htmlspecialchars($input);
}

function showMessage($ms) {
  $text = $ms[0];
  $color = $ms[1];
  echo "<p class='text-$color p-3' style='text-align:center'> $text </p>";
  echo "<script> removeAlert(); </script>";  
}



