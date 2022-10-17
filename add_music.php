<?php
require "header.php";

if (isset($_POST["name"], $_POST["singer"], $_POST["category"], $_POST["detail"], $_FILES["image"], $_FILES["music"])) {
    $name = $_POST["name"];
    $singer = $_POST["singer"];
    $category = $_POST["category"];
    $detail = $_POST["detail"];

    if ($name === "" || $singer === "" || $category === "" || $detail === "") {
        $ok = "فیلد خالی مجاز نیست";
    } elseif ($_FILES["image"]["name"] === "" || $_FILES["music"]["name"] === "") {
        $ok = "لطفا فایل ها را به درستی انتخاب کنید";
    } else {
        $banner_name = rand(1000, 9999) . $_FILES["image"]["name"];
        $music_name = rand(1000, 9999) . $_FILES["music"]["name"];
        $banner_path = "files/" . $banner_name;
        $music_path = "files/" . $music_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $banner_path) &&
            move_uploaded_file($_FILES["music"]["tmp_name"], $music_path)) {
            $ok = add_to_music($name, $singer, $category, $detail, $banner_name, $music_name);
        } else {
            $ok = "خطایی در اپلود فایل وجود دارد. ";
        }
    }
}

$categories = get_categories();
?>
<body class="bg-light" style="direction: rtl">
<main class="container ">
    <?php if (isset($ok) && $ok === true) { ?>
        <div class="alert alert-success" role="alert">
            اهنگ با موفقیت ذخیره شد
        </div>
    <?php } elseif (isset($ok) && $ok !== true) { ?>
        <div class="alert alert-warning" role="alert">
            <?= $ok ?>
        </div>
    <?php } ?>
    <form action="" method="POST" class="mt-4" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal">اضافه کردن اهنگ</h1>
        <div class="mt-4 row mb-3">
            <div class="mb-3">
                <label class="form-label">نام اهنگ<font color="red">*</font></label>
                <input name="name" type="text" class="form-control" required="">
            </div>
            <div class="mb-3">
                <label class="form-label">نام خواننده<font color="red">*</font></label>
                <input name="singer" type="text" class="form-control" required="">
            </div>
            <div class="mb-3">
                <label class="form-label">دسته بندی <font color="red">*</font></label>
                <select name="category" class="form-control">
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category["id"] ?>"> <?= $category["Category_title"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">توضیح<font color="red">*</font></label>
                <textarea name="detail" class="form-control" cols="10" rows="5"></textarea>

            </div>
            <div class="mb-3">
                <label class="form-label">پوستر<font color="red">*</font></label>
                <input name="image" type="file" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">فایل<font color="red">*</font></label>
                <input name="music" type="file" class="form-control">
            </div>

            <button name="submit" class="btn btn-lg btn-primary" type="submit">
                ایجاد و ذخیره
            </button>
    </form>
</main>
</body>