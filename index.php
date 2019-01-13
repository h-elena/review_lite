<?php
include_once 'Reviews.php';
$reviews = (new Reviews())->getAllReviews();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отзывы на сайте</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Отзывы на сайте</h1>
    <div class="row">
        <div class="col-6">
            <form id="form1">
                <div class="form-group">
                    <label for="validationName">Имя</label>
                    <input type="text" class="form-control" name="name" id="validationName" value="" required>
                    <div class="invalid-feedback"></div>
                    <div class="valid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="validationEmail">Email</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" id="validationEmail"
                           name="email" value="" required>
                    <div class="invalid-feedback"></div>
                    <div class="valid-feedback"></div>
                </div>
                <div class="form-group">
                    <label>Текст</label>
                    <textarea class="form-control" name="text" required></textarea>
                    <div class="invalid-feedback"></div>
                    <div class="valid-feedback"></div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-6">&nbsp;</div>
    </div>
    <div class="container reviews">
        <?php if (!empty($reviews)) : ?>
            <?php foreach ($reviews as $review) : ?>
                <div class="block">
                    <div class="row">
                        <div class="col-2">Имя: <?= $review['name'] ?></div>
                        <div class="col-3">Дата: <?= date('d.m.Y H : i : s', strtotime($review['date'])) ?></div>
                        <div class="col-4">E-mail: <?= $review['email'] ?></div>
                    </div>
                    <div class="row">
                        <div class="col-8">Текст: <?= $review['text'] ?></div>
                        <div class="col-4">&nbsp;</div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="script.js"></script>
<style>
    .block {
        margin: 10px 0;
        border-bottom: 1px solid #eee;
    }
</style>
</html>