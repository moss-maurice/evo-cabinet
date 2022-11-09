<h1 class="cab-reg-heading">Подтверждение номера</h1>

<hr>

<form class="form-signin" method="POST">
    <div class="row">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-12">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="mb-1 d-block">Введите полученный код из email / cмс</label>

                                <input class="form-control" type="hidden" name="mobile-phone" id="mobile-phone" placeholder="" value="<?= isset($phone) ? $phone : '' ?>">
                                <input class="form-control" type="hidden" name="firstName" id="firstName" placeholder="" value="<?= isset($firstName) ? $firstName : '' ?>">
                                <input class="form-control" type="hidden" name="email" id="email" placeholder="" value="<?= isset($email) ? $email : '' ?>">

                                <input class="form-control" type="text" name="sms-code" id="smsCode" placeholder="" value="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="msg resMsg"><?= $message; ?></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="cab-reg-button">
                            <button class="b-button b-button_full-width" class="submit">Отправить</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
</div>