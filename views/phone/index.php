<?php
/**
 * @var $this  \controllers\PhoneController
 * @var $phones \models\Phone[]
 * @var $phone  \models\Phone
 */

?>
<script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js"
        type="text/javascript"></script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active" aria-current="page">Справочник</li>
    </ol>
</nav>


<form class="needs-validation" id="create-form">
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="phone">Телефон</label>
            <input type="text" class="form-control" id="phone" name="PhoneForm[phone]" value="78002000000">
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <label for="last_name">Имя</label>
            <input type="text" class="form-control" id="last_name" name="PhoneForm[last_name]" value="">
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <label for="first_name">Фамилия</label>
            <input type="text" class="form-control" id="first_name" name="PhoneForm[first_name]" value="">
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>


    </div>
    <div class="form-row">

        <div class="col-md-6 mb-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="PhoneForm[email]" value="">
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Please select a valid state.
            </div>
        </div>
        <div class="col-md-6 mb-6">
            <div class="form-group">
                <label for="file">Аватар</label>
                <input type="file" class="form-control-file" id="file" name="PhoneForm[file]">
            </div>
        </div>
    </div>

    <button class="btn btn-primary" type="submit" id="create">Добавить запись</button>
</form>

<script>


    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('fsubmit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>


<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Фамилия</th>
        <th scope="col">Имя</th>
        <th scope="col">Телефон</th>
        <th scope="col">Email</th>
        <th scope="col">Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($phones as $phone) {
        ?>
        <tr data-id="<?= $phone->id; ?>">
            <td><?= $phone->first_name; ?></td>
            <td><?= $phone->last_name; ?></td>
            <td><?= $phone->phone; ?></td>
            <td><?= $phone->email; ?></td>
            <td>
                <div class="btn-group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary btn-info edit" data-id="<?= $phone->id; ?>"
                            data-toggle="modal" data-target="#editModal"
                    >
                        <i class="fas fa-pen"></i>
                    </button>
                    <button type="button" class="btn btn-secondary  btn-danger delete" data-id="<?= $phone->id; ?>">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>


<!-- Modal -->
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Редактирование</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
    'use strict';
    window.onload = function () {

        $("#phone").mask("+9 (999) 999-99-99");

        $('#create').click(function () {


            // const formData = $('#create-form').serialize();
            // const formData = new FormData();
            // formData.append( 'file', $( '#file' )[0].files[0] );

            const form = $('#create-form')[0];
            console.log(form);

            // Create an FormData object
            const formData = new FormData(form);

            // If you want to add an extra field for the FormData
            formData.append("CustomField", "This is some extra data, testing");
            $.ajax({
                type: "POST",
                url: "/phone/create",
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                data: formData,
            })
                .done(function (rawResponse) {
                    const response = JSON.parse(rawResponse);
                    if (response.success) {
                        const phone = response.data;
                        $('tbody').prepend(`
                    <tr data-id="${phone.id}">
                             <td>${phone.first_name}</td>
                            <td>${phone.last_name}</td>
                            <td>${phone.phone}</td>
                            <td>${phone.email}</td>
                            <td>
                                         <div class="btn-group" aria-label="Basic example">
                                            <button type="button" class="btn btn-secondary btn-info edit" data-id="${phone.id}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary  btn-danger delete" data-id="${phone.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                            </td>
                    </tr>
                    `
                        );
                        $('#phone').val('');
                        $('#first_name').val('');
                        $('#last_name').val('');
                        return false;
                    } else {
                        const errors = response.errors;
                        console.log(errors);
                    }
                })
                .fail(function (data) {
                    console.log('error');
                    console.log(data);

                    alert("error");
                });

            return false;
        });


        $(document).on('click', '.delete', function () {
                const id = this.getAttribute('data-id');

                $.ajax({
                    type: "POST",
                    url: "/phone/delete/" + id,
                })
                    .done(function (rawResponse) {
                        const response = JSON.parse(rawResponse);
                        if (response.success) {
                            $(`tr[data-id=${id}]`).remove();
                        } else {
                            alert('Что-то пошло не так');
                        }

                    })
                    .fail(function (data) {
                        console.log('error');
                        console.log(data);

                        alert("error");
                    });

                return false;
                // what you want to happen when mouseover and mouseout
                // occurs on elements that match '.dosomething'
            }
        );


    }
</script>