<?php

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\WebUsersModel;

$user = new WebUsersModel;

if (is_array($menu) and !empty($menu)) : ?>
    <div class="cab-menu d-flex align-items-center mb-3 pr-2 --bg-light-grey">
        <ul class="cab-menu-list">
            <?php foreach ($menu as $menuItem) : ?>
                <?php if (in_array($menuItem['role'], array($user->getRole(), 'all'))) : ?>
                    <li>
                        <a href="<?= App::init()->makeUrl($menuItem['link']); ?>"><span class="<?= $menuItem['class'] ?>"></span><?= $menuItem['title'] ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <a href="/lk/logout" class="cab-menu_logout text-decoration-none text-nowrap ml-auto pl-2">
            <i class="fas fa-power-off"></i>
            Выйти
        </a>
    </div>
<?php
endif;
