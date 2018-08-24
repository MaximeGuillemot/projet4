<?php

$dbconfig = array(
    "db_user" => "root",
    "db_pass" => "",
    "db_host" => "localhost",
    "db_name" => "blog"
);

$mailHeaders  = 'MIME-Version: 1.0' . "\r\n";
$mailHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$mailHeaders .= 'From: Jean Forteroche <no-reply@jeanforteroche.fr>'  . "\r\n";
$mailHeaders .= 'Reply-To: no-reply@jeanforteroche.fr'  . "\r\n";
$mailHeaders .= 'Return-Path: no-reply@jeanforteroche.fr'  . "\r\n";

$siteconfig = array(
    "siteUrl" => "http://localhost/projet4/",
    "mailHeaders" => $mailHeaders,
    "activationMessage" => ""
);