<?php

use mmaurice\cabinet\core\App;
?>

<?php //var_dump($authField); ?>

<div class="cab-container d-flex align-items-center flex-grow-1">
    <div class="wr">
        <div id="auth-app" v-cloak>
        <?php
            if ($authField === 'phone') {
                include_once('loginByPhone.php');
            } else {
                include_once('loginByEmail.php');
            }
        ?>
        </div>
    </div>

    <script>
        const authConfig = {
            type: '<?=$authField;?>',
            endPoints: {
                login: '<?= App::init()->makeUrl('/{lk}/api/auth/login/') ?>',
                register: '<?= App::init()->makeUrl('/{lk}/api/auth/register/') ?>',
                remind: '<?= App::init()->makeUrl('/{lk}/api/auth/restore') ?>',    
            }
        }
    </script>

</div>