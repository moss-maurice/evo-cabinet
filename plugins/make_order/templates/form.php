<?php use mmaurice\cabinet\core\App; ?>

<div id="order" class="col-12 text-center mx-0 my-3 px-0 d-flex justify-content-center">
    <div id="form" class="col-9 d-block py-3 bg-light mx-0 border rounded">
        <form method="post" action="<?= App::init()->makeUrl('/{lk}/api/order') ?>">
            <h3 class="py-3">Форма бронирования</h3>

            <?php if (is_array($params) and !empty($params)) : ?>
                <?php foreach ($params as $name => $value) : ?>
                    <input type="hidden" name="<?= $name; ?>" value="<?= $value; ?>" />
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="form-group px-3">
                <label for="comment" class="d-none">Коментарий к заявке</label>
                <textarea name="comment" class="form-control" id="comment" rows="3" placeholder="Оставьте свои пожелания тут ..."></textarea>
            </div>

            <button type="submit" id="order-button" class="btn bs btn-success my-3">Забронировать</button>
        </form>
    </div>
    <div id="success" class="alert d-none alert-success text-center" role="alert">
        <div class="py-3 font-weight-bold">Заявка успешно создана. Вы можете отслеживать её в</div>
        <div>
            <a class="btn bs btn-light" href="">Личном кабинете</a>
        </div>
    </div>
    <div id="fail" class="alert d-none alert-danger text-center" role="alert">
        <div class="py-3 font-weight-bold">К сожалению, что-то пошло не так.<br />Попробуйте снова позже или свяжитесь с администратором.</div>
    </div>
</div>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/css/bootstrap.min.css?v=" . time()); ?>

<?php //$modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/jquery-3.3.1/js/jquery-3.3.1.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/../plugins/make_order/assets/js/script.js?v=" . time()); ?>