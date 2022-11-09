Vue.directive('focus', {
    inserted: function (el) {
        el.focus()
    }
})

const authApp = new Vue({
    el: '#auth-app',
    data: {
        authForm: 'login', // Активная форма
        loginType: authConfig.type,

        // Данные формы
        phone: '',
        password: '',
        email:'',
        name: '',
        smscode: '',
        role: 5,

        // Состояние отправки формы
        isLoginSended: false,
        isRegisterSended: false,
        isRemindSended: false,
        isCheckSended: false,
        
        // Алерты
        isLoginPasswIncorrect: false, // Неверный логин/пас
        isUserRegistered: false,        // Пользователь уже зареген
        isWrongCode: false,             // Ошибочный код sms
        isPasswdSended: false,           // Пароль выслан
        isUserNotFound: false,          // Пользователь не найден

    },
    methods: {
        login() {
            this.isLoginSended = true;

            const data = new FormData();
            data.set('phone', this.phoneNormalize);
            data.set('email', this.email);
            data.set('password', this.password);


            axios
                .post(authConfig.endPoints.login, data)
                .then(resp => {
                    if (resp.data.status == 400) {
                        this.isLoginPasswIncorrect = true
                    }
                    if (resp.data.status == 200) {
                        document.location = resp.data.redirectUrl
                    }
                })
                .catch(err => {
                    console.log(err);

                })
                .finally(() => {
                    this.isLoginSended = false;
                });


        },
        preRegistration() {
            this.isRegisterSended = true;
            this.isUserRegistered = false;

            const data = new FormData();
            data.set('phone', this.phoneNormalize);
            data.set('email', this.email);
            data.set('firstName', this.name);
            data.set('code', this.smscode);
            data.set('role', this.role);

            axios
                .post(authConfig.endPoints.register, data)
                .then(resp => {
                    if (resp.data.status == 400) {
                        this.isUserRegistered = true
                    }
                    if (resp.data.status == 200) {
                        this.authForm = 'checkCode';
                    }
                })
                .catch(err => {
                    console.log(err);
                })
                .finally( () => {
                    this.isRegisterSended = false;
                });
        },

        registration() {
            this.isCheckSended = true;
            this.isWrongCode = false;

            const data = new FormData();
            data.set('phone', this.phoneNormalize);
            data.set('email', this.email);
            data.set('firstName', this.name);
            data.set('code', this.smscode);
            data.set('role', this.role);

            axios
                .post(authConfig.endPoints.register, data)
                .then(resp => {
                    if (resp.data.status == 400) {
                        this.isWrongCode = true
                    }
                    if (resp.data.status == 200) {
                        document.location = resp.data.redirectUrl
                    }
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(() => {
                   this.isCheckSended = false;
                });
        },

        /*
        * Напоминание пароля по SMS или Email
        */
        remind(type = 'email') {
            this.isPasswdSended = true;
            this.isPasswdSended = false;
            this.isUserNotFound = false;

            const data = new FormData();
            data.set('phone', this.phoneNormalize);
            data.set('email', this.email);
            data.set('type', type);

            axios
                .post(authConfig.endPoints.remind, data)
                .then(resp => {
                    if (resp.data.status == 200) {
                        this.isPasswdSended = true
                    }
                    if (resp.data.status == 400) {
                        this.isUserNotFound = true
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.isPasswdSended = true;
                    this.isPasswdSended = false;
                    this.isUserNotFound = false;

                })
                .finally(() => {
                    
                });
        }

    },
    computed: {
        /**
         * Проверяет телефон
         */
        isValidPhone() {
            return /\+\d \(\d{3}\) \d{3}-\d{2}-\d{2}/.test(this.phone)
        },
        /**
         * Проверяет email
         */
        isValidEmail() {
            return /.+@.+\..+/.test(this.email)
        },
        /**
         * Проверяет логин (тел или email)
         */
        isValidLogin() {
            return this.isValidPhone || this.isValidEmail
        },
        /**
         * Заблокирована ли кнопка логина
         */
        isLoginDisabled() {
            return !(!this.isLoginSended && this.isValidLogin && this.password.length >= 4)
        },
        isRegisterDisabled() {
            return !(!this.isRegisterSended && this.isValidEmail && (this.isValidPhone || !this.phone) && this.name)
        },
        isCheckDisabled() {
            return !(!this.isRegisterSended && this.smscode.length == 4)
        },
        isRemindDisabled() {
            return !(!this.isRemindSended)
        },
        phoneNormalize() {
            return this.phone.replace(/[^\d]/g, '')
        }
    },
    mounted: function() {
        var im = new Inputmask("+7 (999) 999-99-99");
        if (this.loginType === 'phone') {
            im.mask(this.$refs.loginphone);
            im.mask(this.$refs.remindphone);
        }
        im.mask(this.$refs.regphone);
        
    }
    
});
