<?php

include_once 'Reviews.php';

$message = '';
$html = '';

if (!empty($_POST)) {
    $form = new Reviews();
    $fields = $form->getFields($_POST);

    if (empty($form->errors)) {
        $form->addReview($fields);
        if (empty($form->errors)) {
            $message = 'Ваш отзыв был успешно добавлен.';
            $html = '<div class="block">
                        <div class="row">
                            <div class="col-2">Имя: ' . $fields['name'] . '</div>
                            <div class="col-3">Дата: ' . date('d.m.Y H : i : s') . '</div>
                            <div class="col-4">E-mail: ' . $fields['email'] . '</div>
                        </div>
                        <div class="row">
                            <div class="col-8">Текст: ' . $fields['text'] . '</div>
                            <div class="col-4">&nbsp;</div>
                        </div>
                    </div>';
            http_response_code(200);
        }
    }

    if (empty($message)) {
        if (!empty($form->errors)) {
            $message = implode('<br>', $form->errors);
        } else {
            $message = 'Не известная ошибка.';
        }

        http_response_code(500);
    }
} else {
    $message = 'Пустой запрос';
    http_response_code(500);
}

echo json_encode([
    'message' => $message,
    'html' => $html
]);