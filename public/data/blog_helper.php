<?php
include_once "../config.php";

function getPosts($conn) {
    $sql = "SELECT p.post_id, p.title, p.content, p.created_at, p.post_image_url, CONCAT(u.first_name, ' ', u.last_name) AS author_name
            FROM posts p
            JOIN users u ON p.user_id = u.user_id
            ORDER BY p.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $posts;
}

function getPost($conn, $post_id) {
    $sql = "SELECT p.post_id, p.title, p.content, p.created_at, p.post_image_url, CONCAT(u.first_name, ' ', u.last_name) AS author_name
            FROM posts p
            JOIN users u ON p.user_id = u.user_id
            WHERE p.post_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $post = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $post;
}

function getComments($conn, $post_id) {
    $sql = "SELECT c.comment, CONCAT(u.first_name, ' ', u.last_name) AS username, u.role, c.created_at 
            FROM comments c 
            JOIN users u ON c.user_id = u.user_id 
            WHERE c.post_id = ? 
            ORDER BY c.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $comments;
}

