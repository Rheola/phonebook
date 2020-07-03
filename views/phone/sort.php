<?php

/**
 * @var \models\Phone[] $phones
 * @var string $attr
 * @var string[] $order
 */
?>

<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">
            <a href="#" class="sort-link" sort-attr="phone" sort-order="<?= $order['phone'] ?>">
                Телефон
            </a>
        </th>
        <th scope="col">
            <a href="#" class="sort-link" sort-attr="last_name" sort-order="<?= $order['last_name'] ?>">
                Фамилия
            </a>
        </th>
        <th scope="col">
            <a href="#" class="sort-link" sort-attr="first_name" sort-order="<?= $order['first_name'] ?>">
                Имя
            </a>
        </th>
        <th scope="col">
            <a href="#" class="sort-link" sort-attr="email" sort-order="<?= $order['email'] ?>">
                Email
            </a>
        </th>
        <th scope="col">Фото</th>
        <th scope="col">Действия</th>
    </tr>
    </thead>
    <tbody class="main">
    <?php

    foreach ($phones as $phone) {
        ?>
        <tr data-id="<?= $phone->id; ?>">
            <td>
                <?= $phone->formattedPhone(); ?>
            </td>
            <td><?= $phone->last_name; ?></td>
            <td><?= $phone->first_name; ?></td>
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
