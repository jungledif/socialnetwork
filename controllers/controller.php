<?php

$action = $_GET["action"] ?? "display";

switch ($action) {

  case 'register':
    // code...
    break;

  case 'logout':
    // code...
    break;

  case 'login':
    // code...
    break;

  case 'newMsg':
    // code...
    break;

  case 'newComment':
    // code...
    break;

  case 'display':
  default:
    include "../models/PostManager.php";
    $posts = GetAllPosts();

    include "../models/CommentManager.php";
    $comments = array();

    foreach ($posts as $onePost) {
      $postId = $onePost['id'];
      $commentForThisPostId = GetAllCommentsFromPostId($postId);
      $comment[$postId] = $commentsForThisPostId;
    }

    include "../views/DisplayPosts.php";
    break;
}
