<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\models\WebUsersModel; ?>

<?php $user = new WebUsersModel; ?>

<header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between pb-3 mb-3 border-bottom">
    <?php if (is_array($menu) and !empty($menu)) : ?>
    <div class="nav col-12 col-md-auto justify-content-center btn-group">
        <?php foreach ($menu as $menuItem) : ?>
        <?php if (in_array($menuItem['role'], array($user->getRole(), 'all'))) : ?>
        <a href="<?= App::init()->makeUrl($menuItem['link']); ?>" class="bs btn btn-primary">
            <span class="<?= $menuItem['class'] ?>"></span> <?= $menuItem['title'] ?>
        </a>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <div class="col-auto p-0 m-0">
        <a href="<?= App::init()->makeUrl('/{lk}/logout'); ?>" type="button" class="bs btn btn-link">
            <i class="fas fa-power-off"></i> Выйти
        </a>
    </div>
</header>