<?php
require "load.php";

if (!isset($_GET["id"])) redirect("index.php");
$id = $_GET["id"];

$music = get_music_by_id($id);

if (file_exists("files/" . $music["banner_file_name"])) unlink("files/" . $music["banner_file_name"]);
if (file_exists("files/" . $music["music_file_name"])) unlink("files/" . $music["music_file_name"]);


delete_music($id);
redirect("index.php");

