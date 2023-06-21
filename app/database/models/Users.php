<?php

namespace app\database\models;

class Users extends Model
{
  public static string $table = 'users';
  public readonly int $id;
  public readonly string $username;
  public readonly string $email;
  public readonly string $password;
  public readonly string $slug; 
  public readonly string $id_hash; 
}
