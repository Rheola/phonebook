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


<form class="needs-validation" id="create-form" method="post">
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="phone">Телефон</label>
            <input type="text" class="form-control" id="phone" name="PhoneForm[phone]" value="78002000000" required>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="first_name">Фамилия</label>
            <input type="text" class="form-control" id="first_name" name="PhoneForm[first_name]" value="Фамилия"
                   required>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="validationCustom02">Last name</label>
            <input type="text" class="form-control" id="validationCustom02" value="Otto" required>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="validationCustom03">City</label>
            <input type="text" class="form-control" id="validationCustom03">
            <div class="invalid-feedback">
                Please provide a valid city.
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label for="validationCustom04">State</label>
            <select class="custom-select" id="validationCustom04">
                <option selected disabled value="">Choose...</option>
                <option>...</option>
            </select>
            <div class="invalid-feedback">
                Please select a valid state.
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label for="validationCustom05">Zip</label>
            <input type="text" class="form-control" id="validationCustom05">
            <div class="invalid-feedback">
                Please provide a valid zip.
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
        <tr>
            <td><?= $phone->first_name; ?></td>
            <td><?= $phone->last_name; ?></td>
            <td><?= $phone->phone; ?></td>
            <td><?= $phone->email; ?></td>
            <td>
                <div class="btn-group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary btn-info edit" data-id="<?= $phone->id; ?>">
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


<script>
    'use strict';
    window.onload = function () {

        $("#phone").mask("+9 (999) 999-99-99");

        $('#create').click(function () {


            const formData = $('#create-form').serialize();
            $.ajax({
                type: "POST",
                url: "/phone/create",
                // dataType: "html",
                data: formData,

            })
                .done(function (rawResponse) {
                    const response = JSON.parse(rawResponse);
                    if (response.success) {
                        const phone = response.data;
                        console.log(phone);
                        $('tbody').prepend(`
                    <tr>
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
                        )
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


        $('.delete').click(function () {


            // const formData = $('#create-form').serialize();
            const id = $(this).attr('data-id');

            console.log(id);
            $.ajax({
                type: "POST",
                url: "/phone/delete?id=" + id,
                // dataType: "html",
                // data: formData,

            })
                .done(function (rawResponse) {


                })
                .fail(function (data) {
                    console.log('error');
                    console.log(data);

                    alert("error");
                });

            return false;
        })

    }
</script>