<?php
/**
 * @var $this  \controllers\PhoneController
 * @var $phones \models\Phone[]
 * @var $phone  \models\Phone
 */

?>
<script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js"
        type="text/javascript"></script>

<script src="/js/phone.js"></script>

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
            <div class="invalid-feedback phone"></div>
        </div>

        <div class="col-md-4 mb-4">
            <label for="last_name">Имя</label>
            <input type="text" class="form-control" id="last_name" name="PhoneForm[last_name]" value="">
            <div class="invalid-feedback last_name"></div>
        </div>

        <div class="col-md-4 mb-4">
            <label for="first_name">Фамилия</label>
            <input type="text" class="form-control" id="first_name" name="PhoneForm[first_name]" value="">
            <div class="invalid-feedback first_name"></div>
        </div>


    </div>
    <div class="form-row">

        <div class="col-md-6 mb-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="PhoneForm[email]" value="">
            <div class="invalid-feedback email"></div>
        </div>

        <div class="col-md-6 mb-6">
            <div class="form-group">
                <label for="file">Аватар</label>
                <input type="file" class="form-control-file" id="file" name="PhoneForm[file]">
                <div class="invalid-feedback file"></div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary" type="submit" id="create">Добавить запись</button>
</form>

<div id="phone-content">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">
                <a href="#" class="sort-link" sort-attr="last_name" sort-order="ASC">Фамилия</a>
            </th>
            <th scope="col">
                <a href="#" class="sort-link" sort-attr="first_name" sort-order="ASC">Имя</a>
            </th>
            <th scope="col">
                <a href="#" class="sort-link" sort-attr="phone" sort-order="ASC">Телефон</a>
            </th>
            <th scope="col">
                <a href="#" class="sort-link" sort-attr="email" sort-order="ASC">Email</a>
            </th>
            <th scope="col">Фото</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($phones as $phone) {
            ?>
            <tr data-id="<?= $phone->id; ?>">
                <td><?= $phone->last_name; ?></td>
                <td><?= $phone->first_name; ?></td>
                <td>
                    <?= $phone->formattedPhone(); ?>
                </td>
                <td><?= $phone->email; ?></td>
                <td>
                    <?php
                    if ($phone->file) {
                        ?>
                        <img src="/upload/min/<?= $phone->file ?>" alt="" class="img-thumbnail">
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-success view" data-id="<?= $phone->id; ?>"
                                data-target="#viewModal">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-info edit" data-id="<?= $phone->id; ?>"
                                data-target="#editModal">
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

</div>


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
                <form class="needs-validation" id="edit-form">
                    <div class="form-row">

                        <div class="col-sm-12  mb-4">
                            <label for="phone">Телефон</label>
                            <input type="text" class="form-control" id="phone-edit" name="PhoneForm[phone]"
                                   value="78002000000">
                            <div class="invalid-feedback phone"></div>
                        </div>

                        <div class="col-sm-12  mb-4">
                            <label for="first_name">Имя</label>
                            <input type="text" class="form-control" id="first_name-edit" name="PhoneForm[first_name]"
                                   value="">
                            <div class="invalid-feedback first_name"></div>
                        </div>


                        <div class="col-sm-12 mb-4">
                            <label for="last_name">Фамилия</label>
                            <input type="text" class="form-control" id="last_name-edit" name="PhoneForm[last_name]"
                                   value="">
                            <div class="invalid-feedback last_name"></div>
                        </div>


                        <div class="col-sm-12  mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email-edit" name="PhoneForm[email]" value="">
                            <div class="invalid-feedback email"></div>
                        </div>

                        <div class="col-sm-12 mb-4">

                            <img id='file-prev' src="/upload/min/" alt="" class="img-thumbnail">

                            <button type="button" class="btn btn-secondary  btn-danger " data-id="" id="delete-file">
                                <i class="fas fa-trash"></i>
                            </button>

                            <div class="form-group">
                                <label for="file">Аватар</label>
                                <input type="file" class="form-control-file" id="file-edit" name="PhoneForm[file]">
                                <div class="invalid-feedback file"></div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit" id="save" data-id="">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Просмотр контакта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-striped">

                    <tbody>
                    <tr>
                        <th scope="row">Телефон</th>
                        <td id="phone-view"></td>
                    </tr>
                    <tr>
                        <th scope="row">Телефон в текстовом представлении</th>
                        <td id="phone-text-view"></td>
                    </tr>
                    <tr>
                        <th scope="row">Имя</th>
                        <td id="first_name-view"></td>

                    </tr>
                    <tr>
                        <th scope="row">Фамилия</th>
                        <td id="last_name-view"></td>

                    </tr>

                    <tr>
                        <th scope="row">Email</th>
                        <td id="email-view"></td>

                    </tr>
                    </tbody>
                </table>


                <div class="col-sm-12 mb-4">

                    <img id='file-view' src="/upload/min/" alt="" class="img-thumbnail">

                </div>

            </div>
        </div>
    </div>
</div>