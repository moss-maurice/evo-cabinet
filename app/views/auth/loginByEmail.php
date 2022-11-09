<!-- ЛОГИН -->
<transition name="slide">
    <div v-show="authForm=='login'">
        <div class="cab-login">
            <h1 class="cab-login-heading text-center mb-2">Войти</h1>

            <form class="form-signin" method="POST" @submit.prevent="login">

                <div class="cab-login-input">
                    <label class="mb-1 d-block" for="login-phone">Email <small>(login)</small> <span class="red">*</span></label>
                    <input type="email" name="email" id="login-phone" required v-model="email" v-focus />
                </div>

                <div class="cab-login-input">
                    <label class="mb-1 d-block" for="password">Пароль <span class="red">*</span></label>
                    <input type="password" name="password" id="password" required v-model="password" />
                </div>

                <div class="alert alert-danger" v-if="isLoginPasswIncorrect">
                    Логин или пароль неверный
                </div>

                <div class="cab-login-reg">
                    <button class="btn btn-success btn-block" type="submit" :disabled="isLoginDisabled">Войти</button>
                </div>


                <div class="cab-login-reg">
                    <button type="button" class="btn btn-outline-secondary" @click="authForm='reg'">Регистрация</button>
                </div>

                <div class="cab-login-reg">
                    <button type="button" class="btn btn-link" @click="authForm='remind'">Напомнить пароль</button>
                </div>

            </form>
        </div>
    </div>
</transition>
<!-- /ЛОГИН -->


<!-- РЕГИСТРАЦИЯ -->
<transition name="slide">
    <div v-show="authForm=='reg'">
        <div class="u-content text-center">
            <p>Здесь Вы можете зарегистрироваться как турист или туристическое агентство. После регистрации Вам будет доступен личный кабинет.</p>
            <p>Если Вы турагентство, то дополнительные функции станут доступны после подтверждения Вашего статуса администраторами.</p>
        </div>
        <div class="cab-login">

            <form class="form-signin" method="POST" @submit.prevent="preRegistration">
                <h1 class="h1 text-center">Регистрация</h1>

                <div class="cab-login-input">
                    <label for="reg-email">E-mail<span class="red">*</span></label>
                    <input type="email" name="email" id="reg-email" required v-model="email" />
                </div>

                <div class="cab-login-input">
                    <label for="reg-tel">Номер мобильного телефона <small>(login)</small></label>
                    <input class="form-control" type="tel" name="mobile-phone" id="reg-tel" v-focus ref="regphone" v-model="phone">
                </div>

                <div class="cab-login-input">
                    <label for="reg-name">Имя<span class="red">*</span></label>
                    <input type="text" name="firstName" id="reg-name" required v-model="name" />
                </div>

                <div class="cab-login-input">
                    <label for="reg-role">Роль</label><br>
                    <label><input type="radio" name="role" id="reg-role" v-model="role" value="5" /> Турист</label> &nbsp;
                    <label><input type="radio" name="role" id="reg-role" v-model="role" value="6" /> Агентство</label>
                </div>

                <div class="form-group">
                    <div class="cab-reg-agree">Нажимая на кнопку «Зарегистрироваться», вы соглашаетесь с <a href="/[~70~]">Условиями использования и Политикой конфиденциальности</a>
                    </div>

                    <div class="alert alert-danger mt-2" v-if="isUserRegistered">
                        Такой пользователь уже зарегистрирован
                    </div>

                    <div class="cab-reg-button">
                        <button class="btn btn-block btn-success" type="submit" :disabled="isRegisterDisabled">Зарегистрироваться</button>
                    </div>

                    <div class="cab-reg-already">
                        Уже зарегистрировались?
                        <button type="button" class="btn btn-link btn-block" @click="authForm='login'">Войти</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</transition>
<!-- /РЕГИСТРАЦИЯ -->

<!-- ПРОВЕРКА EMAIL -->
<transition name="slide">
    <div v-show="authForm=='checkCode'">
        <div class="cab-login">

            <form class="form-signin" method="POST" @submit.prevent="registration">
                <h1 class="h1 text-center">Подтверждение email</h1>

                <div class="cab-login-input text-center">
                    <label for="code" class="mb-1 d-block">Введите полученный код из email <span class="red">*</span></label>
                    <input class="form-control" type="text" name="sms-code" id="code" v-focus v-model="smscode">
                </div>

                <div class="alert alert-danger" v-if="isWrongCode">
                    Неверный код
                </div>

                <div class="cab-reg-button">
                    <button type="submit" class="btn btn-block btn-success" :disabled="isCheckDisabled">ОК</button>
                </div>

                <div class="cab-reg-already">
                    Уже зарегистрировались?
                    <button type="button" class="btn btn-block btn-link" @click="authForm='login'">Войти</button>
                </div>

            </form>
        </div>
    </div>
</transition>
<!-- /ПРОВЕРКА EMAIL -->

<!-- НАПОМНИТЬ -->
<transition name="slide">
    <div v-show="authForm=='remind'">
        <div class="cab-login">

            <form class="form-signin" method="POST" @submit.prevent>
                <h1 class="h1 text-center">Напомнить пароль</h1>

                <div class="cab-login-input">
                    <label for="remind-phone">Email</label>
                    <input class="form-control" type="email" name="remind-phone" id="remind-phone" v-focus ref="remindphone" v-model="email">
                </div>

                <div class="alert alert-success" v-if="isPasswdSended">
                    Пароль отправлен!
                </div>

                <div class="alert alert-danger" v-if="isUserNotFound">
                    Пользователь с таким email не найден
                </div>

                <div class="cab-reg-button">
                    <button class="btn btn-block btn-success" type="submit" :disabled="isRemindDisabled" @click="remind('email')">Выслать пароль на email</button>
                </div>



                <div class="cab-reg-already">
                    Уже зарегистрировались?
                    <button type="button" class="btn btn-link btn-block" @click="authForm='login'">Войти</button>
                </div>


            </form>
        </div>
    </div>
</transition>
<!-- /НАПОМНИТЬ -->