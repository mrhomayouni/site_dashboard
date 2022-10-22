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

function update_music(int $id, string $name, string $singer, int $category_id, string $detail): bool|string
{
    if (update("music", [
        "music_name" => $name,
        "singer" => $singer,
        "category_id" => $category_id,
        "detail" => $detail
    ], ["id" => $id])) {
        return true;
    } else {
        return "خطا در اپدیت موزیک";
    }
}

function upload_music_banner($id, $banner_name): bool|string
{
    if (update("music", ["banner_file_name" => $banner_name], ["id" => $id])) {
        return true;
    } else {
        return "خطا در اپدیت بنر موزیک";
    }
}

function upload_music_file($id, $music_file_name): bool|string
{
    if (update("music", ["music_file_name" => $music_file_name], ["id" => $id])) {
        return true;
    } else {
        return "خطا در اپدیت فایل موزیک";
    }
}

function update2(int $id, string $music_name, string $singer, string $category_id, string $detail, ?string $banner_file_name, ?string $music_file_name)
{
    global $conn;

    $sql = "UPDATE `music` SET `music_name`=:music_name,`singer`=:singer,`category_id`=:category_id,`detail`=:detail";
    if ($banner_file_name !== null) {
        $sql .= ",`banner_file_name`=:banner_file_name";
    }
    if ($music_file_name !== null) {
        $sql .= ",`music_file_name`=:music_file_name";
    }
    $sql .= " WHERE `id`=:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":music_name", $music_name);
    $stmt->bindValue(":singer", $singer);
    $stmt->bindValue(":category_id", $category_id);
    $stmt->bindValue(":detail", $detail);
    if ($banner_file_name !== null) {
        $stmt->bindValue(":banner_file_name", $banner_file_name);
    }
    if ($music_file_name !== null) {
        $stmt->bindValue(":music_file_name", $music_file_name);
    }
    $stmt->bindValue(":id", $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return "حطا در آپدیت موزیک";
    }

}
