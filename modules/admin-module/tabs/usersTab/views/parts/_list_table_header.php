<?php

use mmaurice\cabinet\widgets\SortChevronWidget;

?>

<thead>
    <tr class="paginator">
        <td class="tableHeader" colspan="9">
            <?php include realpath(dirname(__FILE__) . '/_list_pagination.php'); ?>
        </td>
    </tr>
    <tr>
        <td class="tableHeader">#
            <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'id',
        'direction' => (isset($directions['id']) ? $directions['id'] : 'ASC'),
        'active' => (isset($directions['id']) ? true : false),
    ])->draw('id'); ?>
        </td>
        <td class="tableHeader" width="1%">Значок</td>
        <td class="tableHeader">Логин
            <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'login',
        'direction' => (isset($directions['login']) ? $directions['login'] : 'ASC'),
        'active' => (isset($directions['login']) ? true : false),
    ])->draw('login'); ?>
        </td>
        <td class="tableHeader">Полное имя
            <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'name',
        'direction' => (isset($directions['name']) ? $directions['name'] : 'ASC'),
        'active' => (isset($directions['name']) ? true : false),
    ])->draw('name'); ?>
        </td>
        <td class="tableHeader">E-mail
            <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'email',
        'direction' => (isset($directions['email']) ? $directions['email'] : 'ASC'),
        'active' => (isset($directions['email']) ? true : false),
    ])->draw('email'); ?>
        </td>
        <td class="tableHeader">Телефон
            <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'phone',
        'direction' => (isset($directions['phone']) ? $directions['phone'] : 'ASC'),
        'active' => (isset($directions['phone']) ? true : false),
    ])->draw('phone'); ?>
        </td>
        <td class="tableHeader"></td>
    </tr>
</thead>