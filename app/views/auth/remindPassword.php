<?php use mmaurice\cabinet\core\App; ?>

<style>
    .conteiner-login {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        padding: 40px;
    }
    .form-login {
        width: 500px;
        height: fit-content;
        border: 1px solid var(--base);
        border-radius: 10px;
        padding: 30px;
    }
</style>

<div class="conteiner-login">
    <div class="form-login">
        <div class="b-feedback">
            <div class="feedback__header h3">Восстановление пароля</div>
            <form class="b-form form--feedback" id="remind" method="post" action="/lk/api/auth/remind">
                <div class="form__row clr">
                    <label for="form__e" class="form__label">Ваш email</label>
                    <div class="form__field">
                        <input type="email" name="email" id="form__e" class="form__input required" required="">
                    </div>
                </div>	
                <div class="text-center">Нет аккаунта? <a href="/lk/register">Зарегистрироваться</a></div>
                <div class="text-center">Есть аккаунт? <a href="/lk/login">Авторизироваться</a></div>
                <div class="form__msg"></div>
                <div class="form__row clr">
                    <div class="form__field">
                        <input type="submit" name="submit" class="form__submit btn" value="Отправить">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
