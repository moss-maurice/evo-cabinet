<?php use mmaurice\cabinet\widgets\AdminFilteredSelectListWidget; ?>

<script type="text/javascript" src="/admin/media/calendar/datepicker.js"></script>

<div class="row pb-3 filter-area">
    <div class="col-10" id="filter-by">
        <div class="row pb-2">
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>ФИО</small></label>
                <input id="ol-searchClient" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $client; ?>">
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Дата заказа</small></label>
                <div class="dp-container">
                    <input type="text" id="ol-searchOrderDate" name="ol-searchOrderDate" class="DatePicker custom-date-picker" value="<?= $tourDate; ?>" onblur="documentDirty=true;" autocomplete="off">
                    <a class="custom-date-picker" href="javascript:" onclick="document.mutate.dob.value=''; return true;" onmouseover="window.status='Удалить дату'; return true;" onmouseout="window.status=''; return true;">
                        <i class="fa fa-calendar-times-o" title="Удалить дату"></i>
                    </a>
                </div>
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Логин</small></label>
                <input id="ol-searchLogin" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $login; ?>">
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Email</small></label>
                <input id="ol-searchEmail" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $email; ?>">
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Телефон</small></label>
                <input id="ol-searchPhone" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= $phone; ?>">
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>ID тура</small></label>
                <input id="ol-searchId" rel-tab="<?= $tabName; ?>" rel-tab-method="index" class="col-12 form-control" type="text" value="<?= (!is_null($itemId) ? $itemId : ''); ?>">
            </div>
            <div class="col-3">
                <label class="d-block p-0 pl-2 pr-2"><small>Статус заказа</small></label>
                <select name="ol-searchStatus" id="ol-searchStatus" rel-tab="<?= $tabName; ?>" rel-tab-method="index">
                    <option value="0"<?= ((intval($status) === 0) ? ' selected="selected"' : ''); ?>>Все</option>
<?php foreach ($statusList as $statusItem) : ?>
                    <option value="<?= $statusItem['id']; ?>"<?= ((intval($status) === intval($statusItem['id'])) ? ' selected="selected"' : ''); ?>><?= $statusItem['name']; ?></option>
<?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

<?php if (is_array($statusList) and !empty($statusList)) : ?>
    <div class="col-2">
        <div class="pb-2">
            <label class="d-block p-0 pl-2 pr-2"><small>&nbsp;</small></label>
            <div class="btn-group col-12 p-0">
                <span class="btn btn-success col-7" id="ol-searchApply" rel-tab="<?= $tabName; ?>" rel-tab-method="index">Выбрать</span>
                <span class="btn btn-secondary col-5 filter-clear" rel-tab="<?= $tabName; ?>" rel-tab-method="index">Сброс</span>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>
