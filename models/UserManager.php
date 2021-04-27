<?php
include_once "PDO.php";

function GetOneUserFromId($id)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM user WHERE id = :id");
  $response->execute(
    array(
      "id" => $id
    )
  );
  return $response->fetch();
}

function GetAllUsers()
{
  global $PDO;
  $response = $PDO->query("SELECT * FROM user ORDER BY nickname ASC");
  return $response->fetchAll();
}

function GetUserIdFromUserAndPassword($username, $password)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM user WHERE nickname = ':username' AND password = ':password'");
  $response->execute(
    array(
      "username" => $username,
      "password" => $password
    )
  );
  $users = $response->fetchAll();
  $nbOfUsersWithThisPasswordAndNickname = count($users);
  if ($nbOfUsersWithThisPasswordAndNickname == 1) {
    $connectingUser = $users[0];
    return $connectingUser['id'];
  } else {
    return -1;
  }
}
