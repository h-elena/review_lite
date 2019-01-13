"use strict";

/**
 *
 * @param elem
 * @param message
 * @param type
 */
function outputErrorInForm(elem, message, type) {
    if (type == 'error') {
        elem.removeClass('is-valid');
        elem.addClass('is-invalid');
        elem.closest('.form-group').find('.invalid-feedback').text(message);
        elem.closest('.form-group').find('.valid-feedback').text('');
    } else {
        elem.removeClass('is-invalid');
        elem.addClass('is-valid');
        elem.closest('.form-group').find('.invalid-feedback').text('');
        elem.closest('.form-group').find('.valid-feedback').text(message);
    }
}

/**
 *
 * @param elem
 * @param message
 * @param type
 */
function outputMessage(elem, message, type) {
    if (type == 'error') {
        if (elem.find('.alert.alert-danger').length > 0) {
            elem.find('.alert.alert-danger').html(message);
        } else {
            elem.prepend('<div class="alert alert-danger" role="alert">' + message + '</div>');
        }

        if (elem.find('.alert.alert-success').length > 0) {
            elem.closest('form').find('.alert.alert-success').remove();
        }
    } else {
        if (elem.find('.alert.alert-success').length > 0) {
            elem.find('.alert.alert-success').html(message);
        } else {
            elem.prepend('<div class="alert alert-success" role="alert">' + message + '</div>');
        }

        if (elem.find('.alert.alert-danger').length > 0) {
            elem.find('.alert.alert-danger').remove();
        }
    }
}

/**
 *
 * @param elem
 * @returns {boolean}
 */
function validationElement(elem) {
    var errors = [];

    switch (elem.attr('name')) {
        case 'name':
            if (elem.val().match(/^[a-zа-яё\s\-]+$/ui) == null) {
                outputErrorInForm(elem, 'Ваше имя введено не корректно.', 'error');
                errors.push('Ваше имя введено не корректно.');
            } else {
                outputErrorInForm(elem, '', 'success');
                if (elem.val().length < 2) {
                    outputErrorInForm(elem, 'Длина имени должна быть больше единицы.', 'error');
                    errors.push('Длина имени должна быть больше единицы.');
                }
            }

            break;
        case 'email':
            if (elem.val().match(/^[a-zа-яё0-9\-_\.]{1,200}@[a-zа-яё0-9\-_\.]{1,200}\.[a-zа-яё0-9\-_\.]{1,200}$/ui) == null) {
                outputErrorInForm(elem, 'Ваш e-mail введен не корректно.', 'error');
                errors.push('Ваш e-mail введен не корректно.');
            } else {
                outputErrorInForm(elem, '', 'success');
                if (elem.val().length < 2) {
                    outputErrorInForm(elem, 'Длина e-mail должна быть больше 3.', 'error');
                    errors.push('Длина e-mail должна быть больше 3.');
                }
            }

            break;
        default:
            if (elem.val().length < 2) {
                outputErrorInForm(elem, 'Длина сообщения должна быть больше единицы.', 'error');
                errors.push('Длина сообщения должна быть больше единицы.');
            }

            break;
    }

    if (elem.prop('required') && elem.val().length == 0) {
        outputErrorInForm(elem, 'Это поле обязательно для заполнения.', 'error');
        errors.push('Это поле обязательно для заполнения.');
    }

    if (errors.length > 0) {
        return errors;
    }

    return true;
}

$(function () {
    $('#form1 input.form-control').change(function () {
        validationElement($(this));
    });

    $('#form1 button[type=submit]').click(function () {
        var errors = [], validate;
        var elem = $(this);
        elem.closest('form').find('input.form-control').each(function () {
            if (validate = validationElement($(this)) != true) {
                errors.push(validate);
            }
        });

        if (errors.length > 0) {
            outputMessage(elem.closest('form'), errors.join('<br>'), 'error');

            return false;
        }

        var data = elem.closest('form').serialize();

        $.ajax({
            url: '/ajax.php',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (result) {
                if (result.message) {
                    outputMessage(elem.closest('form'), result.message, 'success');

                    if (result.html) {
                        console.log(elem.closest('.row'))
                        console.log(elem.closest('.row').next())
                        elem.closest('.row').next().prepend(result.html);
                    }
                }
            },
            error: function (result) {
                if (result.message) {
                    outputMessage(elem, result.message, 'error');
                }
            }
        });

        return false;
    });
});