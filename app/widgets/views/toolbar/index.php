<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\models\WebUsersModel; ?>
<?php $user = new WebUsersModel; ?>
<!-- Widget Toolbar block [ -->
<?php
/*
?>
<div class="cab-panel">
    <div class="row">
        <div class="col-sm-7 panel-mes">

        </div>
        <div class="col-sm-5 panel-profile">
            <div class="profile-name">
                <a class="profile-name-link">
                    <img class="panel-photo" src="<?= $user->getAvatar() ?>"
                         alt=""><?= $user->getSetting('first-name', '') ?> <?= $user->getSetting('last-name', '') ?>
                </a>

				<?php if (is_array($menu) and !empty($menu)) : ?>
                    <div class="profile-widget">
                        <ul class="profile-menu-list">
							<?php foreach ($menu as $menuItem) : ?>
								<?php if (in_array($menuItem['role'], array($user->getRole(), 'all'))) : ?>
                                    <li>
                                        <a href="<?= App::init()->makeUrl($menuItem['link']); ?>"><span
                                                    class="<?= $menuItem['class'] ?>"></span><?= $menuItem['title'] ?>
                                        </a>
                                    </li>
								<?php endif; ?>
							<?php endforeach; ?>
                        </ul>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
*/?>
<!-- ] Widget Toolbar block -->
