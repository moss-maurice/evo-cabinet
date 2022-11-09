    <h2>Личная информация</h2>
    <table width="100%">
        <tbody>
            <tr>
                <td colspan="2">
                    <hr />
                </td>
            </tr>
            <tr>
                <td class="width200px">
                    <span class="warning">Логин пользователя</span>
                </td>
                <td>
                    <strong><?= $userItem['username']; ?></strong>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Дата регистрации</span>
                </td>
                <td>
                    <strong><?= date('Y-m-d H:i:s', intval($userItem['attributes']['createdon'])); ?></strong>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Дата последнего обновления</span>
                </td>
                <td>
                    <strong><?= date('Y-m-d H:i:s', intval($userItem['attributes']['editedon'])); ?></strong>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr />
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Фамилия</span>
                </td>
                <td>
                    <input name="last-name" type="text" maxlength="100" value="<?= $userItem['settings']['last_name']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Имя</span>
                </td>
                <td>
                    <input name="first-name" type="text" maxlength="100" value="<?= $userItem['settings']['first_name']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Отчество (если имеется)</span>
                </td>
                <td>
                    <input name="middle-name" type="text" maxlength="100" value="<?= $userItem['settings']['middle_name']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Дата рождения</span>
                </td>
                <td>
                    <div class="dp-container">
                        <input type="text" id="dob" name="dob" class="DatePicker custom-date-picker" value="<?= date('d-m-Y', intval($userItem['attributes']['dob'])); ?>" onblur="documentDirty=true;" autocomplete="off">
                        <a class="custom-date-picker" href="javascript:" onclick="document.mutate.dob.value=''; return true;" onmouseover="window.status='Удалить дату'; return true;" onmouseout="window.status=''; return true;">
                            <i class="fa fa-calendar-times-o" title="Удалить дату"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr />
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Основной телефон</span>
                </td>
                <td>
                    <input name="phone" type="text" maxlength="100" value="<?= $userItem['attributes']['phone']; ?>" class="inputBox width200px">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Дополнительный телефон</span>
                </td>
                <td>
                    <input name="mobilephone" type="text" maxlength="100" value="<?= $userItem['attributes']['mobilephone']; ?>" class="inputBox width200px">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">E-mail</span>
                </td>
                <td>
                    <input name="email" type="text" maxlength="100" value="<?= $userItem['attributes']['email']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Факс</span>
                </td>
                <td>
                    <input name="fax" type="text" maxlength="100" value="<?= $userItem['attributes']['fax']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr />
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Гражданство</span>
                </td>
                <td>
                    <select name="country" style="max-width: 200px;">
<?php if (is_array($countries) and !empty($countries)) : ?>
    <?php foreach ($countries as $country) : ?>
                        <option <?= ((intval($country['id']) === (intval($userItem['attributes']['country']) !== 0 ? intval($userItem['attributes']['country']) : 150)) ? 'selected ' : '') ?>value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
    <?php endforeach; ?>
<?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Регион</span>
                </td>
                <td>
                    <input name="state" type="text" maxlength="100" value="<?= $userItem['attributes']['state']; ?>" class="inputBox width200px">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Город</span>
                </td>
                <td>
                    <input name="city" type="text" maxlength="100" value="<?= $userItem['attributes']['city']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="warning">Улица</span>
                </td>
                <td>
                    <input name="street" type="text" maxlength="100" value="<?= $userItem['attributes']['street']; ?>" class="inputBox">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr />
                </td>
            </tr>
        </tbody>
    </table>
