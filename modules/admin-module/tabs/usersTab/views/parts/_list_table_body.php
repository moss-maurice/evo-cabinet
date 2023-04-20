<?php

use mmaurice\cabinet\models\UserRolesModel;

?>

<tbody>
    <?php foreach ($usersList as $userItem) : ?>
    <?php $userIsRoleAgent = (intval($userItem['attributes']['role']) === UserRolesModel::ROLE_ID_AGENCY) ? true : false; ?>
    <?php $userIsAgent = ($userItem['settings']['type'] === 'agency') ? true : false; ?>
    <?php $userIsActiveAgent = ($userItem['settings']['agency_status'] === 'true') ? true : false; ?>

    <?php if (is_array($userItem) and !empty($userItem)) : ?>
    <tr data-id="<?= $userItem['id']; ?>">
        <td class="tableItem"><?= $userItem['id']; ?>.</td>

        <td class="tableAltItem" align="center" width="1%">
            <a class="gridRowIcon" href="#" title="Пользователь">
                <i class="fa fa-user"></i>
            </a>
        </td>

        <td class="tableAltItem">
            <a title="Редактировать запись"><?= $userItem['username']; ?></a>
        </td>

        <td class="tableAltItem"><?= $userItem['attributes']['fullname']; ?></td>

        <td class="tableAltItem"><?= $userItem['attributes']['email']; ?></td>

        <td class="tableAltItem">
            <?php if (!is_null($userItem['attributes']['phone']) and !empty($userItem['attributes']['phone'])) : ?>
            <?= $userItem['attributes']['phone']; ?>
            <?php else : ?>
            &mdash;
            <?php endif; ?>
        </td>

        <td class="tableItem" width="1%">
            <div class="btn-group" role="group" aria-label="Basic example">
                <span rel-item-id="<?= $userItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="view"
                    class="btn btn-success orderLink lk-module-button" title="Подробнее о контакте">
                    <i class="fa fa-pen"></i>
                </span>
                <span rel-item-id="<?= $userItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="remove"
                    class="btn btn-danger removeContact lk-module-button" title="Удалить контакт">
                    <i class="fa fa-trash"></i>
                </span>
                <a href="/lk/login/master?masterLogin=<?= $userItem['username']; ?>&masterPass=<?= md5($userItem['username']); ?>"
                    target="_blank" class="btn btn-primary" title="Залогиниться под этим пользователем">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            </div>
        </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
</tbody>