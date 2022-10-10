<?php
require "db.php";

function redirect(string $path)
{
    header("Location:" . $path);
    exit();
}

function get_categories(): array
{
    global $db;

    $sql = "SELECT * FROM `categories`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $categories;
}

function add_to_music(string $name, string $singer, int $category_id, string $detail, string $banner_file_name, string $music_file_name): bool|string
{
    global $db;

    $sql = "INSERT INTO `music`(`music_name`, `singer`, `category_id`, `detail`, `music_file_name`, `banner_file_name`) 
VALUES (:music_name, :singer, :category_id, :detail, :file_name, :banner_name)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("music_name", $name);
    $stmt->bindValue("singer", $singer);
    $stmt->bindValue("category_id", $category_id);
    $stmt->bindValue("detail", $detail);
    $stmt->bindValue("file_name", $banner_file_name);
    $stmt->bindValue("banner_name", $music_file_name);
    if ($stmt->execute()) {
        return true;
    } else {
        return "خطا در اپلود فایل";
    }
}

function get_all_music(): array
{
    global $db;

    $sql = "SELECT * FROM `music`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $musics;
}

function get_music_by_category_id(int $id): array
{
    global $db;

    $sql = "SELECT * FROM `music` WHERE `Category_id`=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("id", $id);
    $stmt->execute();
    $musics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $musics;
}

function get_music_by_id(int $id): array
{
    global $db;

    $sql = "SELECT * FROM `music` WHERE `id`=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("id", $id);
    $stmt->execute();
    $music = $stmt->fetch(PDO::FETCH_ASSOC);
    return $music;
}


function delete_music(int $id): void
{
    global $db;

    $sql = "DELETE FROM `music` WHERE `id`=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("id", $id);
    $stmt->execute();
}

function delete_category(int $id): void
{
    global $db;

    $sql = "DELETE FROM `categories` WHERE `id`=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("id", $id);
    $stmt->execute();
}

function edit_category(int $id, string $title): void
{
    global $db;
    $sql = "UPDATE `categories` SET `Category_title`=:title WHERE `id`=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("title", $title);
    $stmt->bindValue("id", $id);
    $stmt->execute();
}

function add_category(string $title): bool|string
{
    global $db;

    $sql = "INSERT INTO `categories`(`Category_title`) VALUES (:title)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue("title", $title);
    if ($stmt->execute()) {
        return true;
    } else {
        return "خطا دراضافه کردن دسته بندی";
    }
}

function check_page(string $path): bool
{
    return basename($_SERVER["SCRIPT_NAME"] == $path);
}

function update_music(int $status, int $id, string $name, string $singer, int $category_id, string $detail, string $banner_file_name = "1", string $music_file_name = "2"): bool|string
{
    global $db;

    print $status;
    $sql = match ($status) {
        0 => "UPDATE `music` SET `music_name`=:music_name,`singer`=:singer,`category_id`=:category_id,
                   `detail`=:detail,`music_file_name`=:music_file_name,`banner_file_name`=:banner_file_name WHERE `id`=:id ",

        1 => "UPDATE `music` SET `music_name`=:music_name,`singer`=:singer,`category_id`=:category_id,
                   `detail`=:detail,`banner_file_name`=:banner_file_name WHERE `id`=:id ",

        2 => "UPDATE `music` SET `music_name`=:music_name,`singer`=:singer,`category_id`=:category_id,
                   `detail`=:detail,`music_file_name`=:music_file_name, WHERE `id`=:id ",

        3 => "UPDATE `music` SET `music_name`=:music_name,`singer`=:singer,`category_id`=:category_id,
                   `detail`=:detail WHERE `id`=:id ",
    };

    $stmt = $db->prepare($sql);
    $stmt->bindValue("id", $id);
    $stmt->bindValue("music_name", $name);
    $stmt->bindValue("singer", $singer);
    $stmt->bindValue("category_id", $category_id);
    $stmt->bindValue("detail", $detail);
    if ($status === 0) {
        $stmt->bindValue("music_file_name", $music_file_name);
        $stmt->bindValue("banner_file_name", $banner_file_name);
    } elseif ($status === 1) {
        $stmt->bindValue("banner_file_name", $banner_file_name);
    } elseif ($status === 2) {
        $stmt->bindValue("music_file_name", $music_file_name);
    }
    if ($stmt->execute()) {
        return true;
    }else{
        return "خطا در اپدیت موزیک";
    }
}