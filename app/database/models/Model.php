<?php

namespace app\database\models;

use app\database\Transaction;
use PDOException;
use PDO;

abstract class Model
{
  protected static string $table;

  public static function all(string $fields = '*', int $limit = 12,  string $order = 'ASC', array $type = ['scan', 'original'])
  {
    try {
      Transaction::open();

      $conn = Transaction::getConnection();
      $tableName = static::$table;

      $where = sprintf("('%s')", implode("','", $type));

      $query = $conn->prepare("select {$fields} from {$tableName} WHERE format IN {$where} ORDER BY ID {$order} LIMIT {$limit}");

      $query->execute();

      $fetch = $query->fetchAll(PDO::FETCH_ASSOC);

      Transaction::close();
      return array_map(fn (string $json) => json_decode($json, true), array_column($fetch, "value"));
    } catch (PDOException $e) {
      Transaction::rollback();
    }
  }


  public static function save(array $data =  [])
  {
    try {
      Transaction::open();

      $conn = Transaction::getConnection();
      $tableName = static::$table;

      $keys = $values = [];

      foreach ($data as $key => $value) $keys[] = "`$key`" and $values[] = $value == '' ? 'NULL' : "'$value'";

      $query = $conn->prepare("insert into `$tableName` (" . implode(',', $keys) . ") values (" . implode(',', $values) . ")");
      $query->execute();
      Transaction::close();
    } catch (PDOException $e) {
      Transaction::rollback();
    }
  }

  public static function update(array $data = [],  $id = '')
  {

    try {

      Transaction::open();

      $conn = Transaction::getConnection();
      $tableName = static::$table;

      $set = [];
      foreach ($data as $key => $value) $set[] = "$key = '$value'";

      $query = $conn->prepare("update `$tableName` set " . implode(', ', $set) . " where id = '$id'"); 
      $query->execute();  
      Transaction::close();
    } catch (PDOException $e) { 
      Transaction::rollback();
    }
  }

  public static function where(string $field, string $value, string $fields = '*')
  {
    try {
      Transaction::open();

      $conn = Transaction::getConnection();
      $tableName = static::$table;

      $query = $conn->prepare("select {$fields} from {$tableName} where {$field} = :{$field}");
      $query->execute([$field => $value]);

      Transaction::close();
      return $query->fetchObject(static::class);
    } catch (PDOException $e) {
      Transaction::rollback();
    }
  }

  public static function whereFetchAll(string $field, string $value, string $fields = '*')
  {
    try {
      Transaction::open();

      $conn = Transaction::getConnection();
      $tableName = static::$table;

      $query = $conn->prepare("select {$fields} from {$tableName} where {$field} = :{$field}");
      $query->execute([$field => $value]);

      Transaction::close();
      return $query->fetchAll(PDO::FETCH_CLASS, static::class);
    } catch (PDOException $e) {
      Transaction::rollback();
    }
  }

  public static function whereFetch(string $field, string $value, string $fields = '*')
  {
    try {
      Transaction::open();

      $conn = Transaction::getConnection();
      $tableName = static::$table;

      $query = $conn->prepare("select {$fields} from {$tableName} where {$field} = :{$field}");
      $query->execute([$field => $value]);

      Transaction::close();
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      Transaction::rollback();
    }
  }
}
