<?php
require "db.php";

function redirect(string $path)
{
    header("Location:" . $path);
    exit();
}

function get_categories(): array
{
    return select("categories");
}

function add_to_music(string $name, string $singer, int $category_id, string $detail, string $banner_file_name, string $music_file_name): bool|string
{
    if (insert("music", [
        "music_name" => $name,
        "singer" => $singer,
        "category_id" => $category_id,
        "detail" => $detail,
        "music_file_name" => $music_file_name,
        "banner_file_name" => $banner_file_name
    ])) {
        return true;
    } else {
        return "خطا در اپلود فایل";
    }

}

function get_all_music(): array
{
    return select("music");
}

function get_music_by_category_id(int $id): array
{
    return select("music", [], ["Category_id" => $id]);
}

function get_music_by_id(int $id): array
{

    return select("music", [], ["id" => $id])[0];
}


function delete_music(int $id): void
{
    delete("music", ["id" => $id]);
}

function delete_category(int $id): void
{
    delete("categories", ["id" => $id]);
}

function edit_category(int $id, string $title): void
{
    global $conn;
    $sql = "UPDATE `categories` SET `Category_title`=:title WHERE `id`=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue("title", $title);
    $stmt->bindValue("id", $id);
    $stmt->execute();
}

function add_category(string $title): bool|string
{
    if (insert("categories", ["Category_title" => $title])) {
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
    global $conn;

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

    $stmt = $conn->prepare($sql);
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
    } else {
        return "خطا در اپدیت موزیک";
    }
}