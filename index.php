<?php
require "header.php";

$categories = get_categories();
$musics = get_all_music();
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
<body style="direction: rtl">
<!-- https://getbootstrap.com/docs/5.2/examples/album-rtl/-->

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div>
                    <ul class="list-group">
                        <?php foreach ($categories as $category) { ?>
                            <li class="list-group-item"><a
                                        href="music_category.php?id=<?= $category["id"] ?>"> <?= $category["Category_title"] ?> </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class=" col-sm-9 ">
                <div class="row  row-cols-sm-2 row-cols-md-4 g-3">
                    <?php foreach ($musics as $music) { ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <img class="bd-placeholder-img card-img-top" width="150" height="190"
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
                                            <a href="edit.php?id=<?= $music["id"] ?>"                                               class="add btn btn-sm btn-outline-secondary"
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
    </div>
</div>
</body>
</html>