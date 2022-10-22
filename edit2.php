<?php
require "header.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    redirect("index.php");
}
$music = get_music_by_id($id);
if (isset($_POST["name"], $_POST["singer"], $_POST["category"], $_POST["detail"])) {
    $name = $_POST["name"];
    $singer = $_POST["singer"];
    $category = $_POST["category"];
    $detail = $_POST["detail"];

    if ($name == "" || $singer == "" || $category == "" || $detail == "") {
        $ok = "فیلد خالی مجاز نیست";
    } else {

        $banner_name = null;
        $music_name = null;

        if (isset($_FILES["music"], $_FILES["image"])) {
            if ($_FILES["image"]["name"] !== "") {
                $banner_name = rand(1000, 9999) . $_FILES["image"]["name"];
                $banner_path = "files/" . $banner_name;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $banner_path)) {
                    if (file_exists("files/" . $music["banner_file_name"])) {
                        unlink("files/" . $music["banner_file_name"]);
                    }
                }
            }

            if ($_FILES["music"]["name"] !== "") {
                $music_name = rand(1000, 9999) . $_FILES["music"]["name"];
                $music_path = "files/" . $music_name;

                if (move_uploaded_file($_FILES["music"]["tmp_name"], $music_path)) {
                    if (file_exists("files/" . $music["music_file_name"])) {
                        unlink("files/" . $music["music_file_name"]);
                    }
                }
            }
        }

        $ok = update2($id, $name, $singer, $category, $detail, $banner_name, $music_name);
    }
}
$music = get_music_by_id($id);

$categories = get_categories();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
                integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
                integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz"
                crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT"
              crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
              integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N"
              crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    </head>


    <body class="bg-light" dir="rtl">
    <main class="container ">
        <div class="row">
            <div class="col-sm-3" style="margin-top: 10rem">
                <div class="col" style="width: 250px">
                    <div class="card shadow-sm">
                        <img class="bd-placeholder-img card-img-top" width="100%" height="100%"
                             src="files/<?= $music["banner_file_name"] ?>">
                        <div class="card-body">
                            <p class="card-text"
                               style="text-align: center"><?= $music["music_name"] ?></p>
                            <p class="card-text"
                               style="text-align: center"><?= $music["singer"] ?></p>
                            <audio controls
                                   style="display: table;margin-left: auto; margin-right: auto; width: 90%"
                                   class="mb-4">
                                <source src="files/<?= $music["music_file_name"] ?>"
                                        type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group"
                                     style="margin-left: auto;margin-right: auto;">
                                    <a href="delete.php?id=<?= $music["id"] ?>"
                                       class="delete btn btn-sm btn-outline-secondary"
                                       style="border-top-right-radius: 5px;border-bottom-right-radius: 5px;">
                                        <i class="bi bi-trash"></i></a>
                                    <a href="edit.php?id=<?= $music["id"] ?>"
                                       class="add btn btn-sm btn-outline-secondary"
                                       style="border-top-left-radius: 5px;border-bottom-left-radius: 5px;">
                                        <i class="bi bi-pencil"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <?php if (isset($ok) && $ok === true) { ?>
                    <div class="alert alert-success" role="alert">
                        تغییرات با موفقیت ثبت شد
                    </div>
                <?php } elseif (isset($ok) && $ok !== true) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?= $ok ?>
                    </div>
                <?php } ?>

                <form action="" method="POST" class="mt-4" enctype="multipart/form-data">
                    <h1 class="h3 mb-3 fw-normal">ویرایش اهنگ</h1>
                    <div class="mt-4 row mb-3">
                        <div class="mb-3">
                            <label class="form-label">نام اهنگ<font color="red">*</font></label>
                            <input name="name" type="text" class="form-control" required=""
                                   value="<?= $music["music_name"] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نام خواننده<font color="red">*</font></label>
                            <input name="singer" type="text" class="form-control" required=""
                                   value="<?= $music["singer"] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">دسته بندی <font color="red">*</font></label>
                            <select name="category" class="form-control">
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?= $category["id"] ?>" <?php if ($music["category_id"] === $category["id"]) { ?>  selected <?php } ?> > <?= $category["Category_title"] ?>  </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیح<font color="red">*</font></label>
                            <textarea name="detail" class="form-control" cols="10"
                                      rows="5"> <?= $music["detail"] ?> </textarea>

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
                    </div>
                </form>
            </div>
        </div>
    </main>
    </body>
    </html>
<?php
