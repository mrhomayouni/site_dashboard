<?php
require "header.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    redirect("index.php");
}
$musics = get_music_by_category_id($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>تست</title>
    <style>
        a {
            text-decoration: none
        }
    </style>
</head>
<body>
<!-- https://getbootstrap.com/docs/5.2/examples/album-rtl/-->
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="row  row-cols-sm-2 row-cols-md-4 g-3">
                <?php foreach ($musics as $music) { ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <img class="bd-placeholder-img card-img-top" width="200" height="250"
                                 src="files/<?= $music["banner_file_name"] ?>">
                            <div class="card-body">
                                <p class="card-text" style="text-align: center"><?= $music["music_name"] ?></p>
                                <p class="card-text" style="text-align: center"><?= $music["singer"] ?></p>
                                <audio controls
                                       style="display: table;margin-left: auto; margin-right: auto; width: 90%"
                                       class="mb-4">
                                    <source src="files/<?= $music["music_file_name"] ?>" type="audio/mpeg">
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
                <?php } ?>

            </div>
        </div>
    </div>
</body>
</html>