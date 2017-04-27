<?php

use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', "WriterBlog\Controller\HomeController::indexAction")
    ->bind('home');

// Chapter individual page
$app->match('/chapter/{id}', "WriterBlog\Controller\HomeController::chapterAction")
    ->bind('chapter');

// Login form
$app->get('/login', "WriterBlog\Controller\HomeController::loginAction")
    ->bind('login');

$app->match('/signin', "WriterBlog\Controller\HomeController::signInAction")
    ->bind('signin');

// Admin home page
$app->get('/admin', "WriterBlog\Controller\AdminController::indexAction")
   ->bind('admin');

// Add a new chapter
$app->match('/admin/chapter/add', "WriterBlog\Controller\AdminController::addChapterAction")
    ->bind('admin_chapter_add');

// Modify an existing chapter
$app->match('/admin/chapter/modify/{id}', "WriterBlog\Controller\AdminController::modifyChapterAction")
    ->bind('admin_chapter_modify');

// Remove a chapter
$app->get('/admin/chapter/delete/{id}',"WriterBlog\Controller\AdminController::deleteChapterAction")
    ->bind('admin_chapter_delete');

// Modify an existing comment
$app->match('/admin/comment/modify/{id}',"WriterBlog\Controller\AdminController::modifyCommentAction")
    ->bind('admin_comment_modify');

// Delete an existing comment and its children
$app->get('/admin/comment/delete/{id}',"WriterBlog\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// Add a new user
$app->match('/admin/user/add', "WriterBlog\Controller\AdminController::addUserAction")
    ->bind('admin_user_add');

// Modify a user matching id
$app->match('/admin/user/modify/{id}', "WriterBlog\Controller\AdminController::modifyUserAction")
    ->bind('admin_user_modify');

// Delete an existing comment and its children
$app->get('/admin/user/delete/{id}',"WriterBlog\Controller\AdminController::deleteUserAction")
    ->bind('admin_user_delete');