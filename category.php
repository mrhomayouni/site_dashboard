<?php
require "header.php";

if (isset($_POST["delete"])) {
    $id = $_POST["delete"];
    delete_category($id);
}
if (isset($_POST["send"])) {
    $id = $_POST["id"];
    $new_title = $_POST["new_title"];
    edit_category($id, $new_title);
}
if (isset($_POST["add"])) {
    $title = $_POST["title"];
    if ($title !== "") {
        add_category($title);
    } else {
        $ok = "عنوان دسته بندی را اضافه کنید.";
    }
}
$categories = get_categories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>دسته بندی ها</title>
</head>
<body style="direction: rtl">

<div class="container">
    <?php if (isset($ok) && $ok === true) { ?>
        <div class="alert alert-success" role="alert">
            مشخصات با موفقیت تغییر کرد
        </div>
    <?php } elseif (isset($ok) && $ok !== true) { ?>
        <div class="alert alert-warning" role="alert">
            <?= $ok ?>
        </div>
    <?php } ?>
    <h2>دسته بندی ها</h2>
    <div class="d-flex">
        <form action="" method="post">
            <button class="btn btn-primary mb-4" name="add_category" style="margin-left:4px "> افزودن دسته بندی جدید
            </button>
        </form>
        <?php if (isset($_POST["add_category"])) { ?>
            <form action="" method="post" class="d-flex mb-4">
                <input class="form-control" style="width: 250px" type="text" name="title"
                       placeholder="عنوان دسته بندی را وارد کنید">
                <input class="btn btn-primary" style="margin-right: 4px" type="submit" name="add" value="افزودن">
            </form>
        <?php } ?>
    </div>
    <table class="table table-success table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>نام دسته</th>
            <th>مدیریت</th>
            <?php if (isset($_POST["edit"])) { ?>
                <th></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $category) { ?>
            <tr>
            <td><?= $category["id"] ?></td>
            <td><?= $category["Category_title"] ?></td>
            <td>
                <div>
                    <form action="" method="post">
                        <button class="btn" name="delete" value="<?= $category["id"] ?>"><i class="bi bi-trash"></i>
                        </button>
                        <button class="btn" name="edit" value="<?= $category["id"] ?>"><i class="bi bi-pencil"></i>
                        </button>
                    </form>
                </div>
            </td>
            <?php if (isset($_POST["edit"])) { ?>
                <td>
                    <form action="" method="post" class="d-flex">
                        <input class="form-control" style="width: 300px;background-color: #f5f5f5" type="text"
                               name="new_title"
                               placeholder="عنوان جدید را اضافه کنید">
                        <input type="hidden" name="id" value="<?= $category["id"] ?>">
                        <input class="btn" type="submit" value="send" name="send">
                    </form>
                </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
</div>
</body>
</html>

