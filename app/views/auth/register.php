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
            <div class="feedback__header h3">Регистрация</div>
            <form class="b-form form--feedback" id="sign-up" method="post" action="/lk/api/auth/register">
                <div class="form__row clr">
                    <label for="form__e" class="form__label">Ваш имя</label>
                    <div class="form__field">
                        <input type="text" name="firstName" id="form__e" class="form__input required" required="">
                    </div>
                </div>
                <div class="form__row clr">
                    <label for="form__e" class="form__label">Ваш email</label>
                    <div class="form__field">
                        <input type="email" name="email" id="form__e" class="form__input required" required="">
                    </div>
                </div>
                <div class="form__row clr">
                    <label for="form__e" class="form__label">Ваш пароль</label>
                    <div class="form__field">
                        <input type="password" name="password" id="form__e" class="form__input required" required="">
                    </div>
                </div>
                <div class="form__row clr">
                    <label for="form__e" class="form__label">Пароль ещё раз</label>
                    <div class="form__field">
                        <input type="password" name="passwordRetype" id="form__e" class="form__input required" required="">
                    </div>
                </div>
                <div class="text-center">Уже есть аккаунт? <a href="/lk/login">Авторизироваться</a></div>
                <div class="form__msg"></div>
                <div class="form__row clr">
                    <div class="form__field">
                        <input type="submit" name="submit" class="form__submit btn" value="Регистрация">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
