<?php
use mmaurice\cabinet\models\UserRolesModel;
?>

<div class="row pb-3 filter-area">
    <div class="col-10" id="filter-by">
        <div class="row pb-2">
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Логин</small></label>
                <input id="ol-filterLogin" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $login; ?>">
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Email</small></label>
                <input id="ol-filterEmail" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $email; ?>">
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Телефон</small></label>
                <input id="ol-filterPhone" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $phone; ?>">
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="pb-2">
            <label class="d-block p-0 pl-2 pr-2"><small>&nbsp;</small></label>
            <div class="btn-group col-12 p-0">
                <span class="btn btn-success col-7" id="ol-filterApply" rel-tab="<?= $tabName; ?>" rel-tab-method="index">Выбрать</span>
                <span class="btn btn-secondary col-5 filter-clear" rel-tab="<?= $tabName; ?>" rel-tab-method="index">Сброс</span>
            </div>
        </div>
    </div>
</div>
