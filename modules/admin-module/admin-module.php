<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
ini_set('memory_limit', '1024M');

setlocale(LC_ALL, 'ru_RU.utf8');

if (IN_MANAGER_MODE != 'true' or empty($modx) or !($modx instanceof DocumentParser)) {
    die('Please use the MODX Content Manager instead of accessing this file directly.');
}

if (!$modx->hasPermission('exec_module')) {
    $modx->sendRedirect('index.php');
}

if (!is_array($modx->event->params)) {
    $modx->event->params = [];
}

require_once realpath(dirname(__FILE__) . '/../../vendor/autoload.php');

/*
preg_match_all('/([^\=\&]+)\=([^\&$]+)/i', str_replace('&amp;', '&', trim($_SERVER['QUERY_STRING'])), $matches);
$request = array_combine($matches[1], $matches[2]);
*/
$request = $_REQUEST;

require_once realpath(dirname(__FILE__) . '/classes/tabClass.php');

$tabsPath = realpath(dirname(__FILE__) . '/tabs/');
$tabsList = scandir($tabsPath);

$tabs = [];

if (is_array($tabsList) and !empty($tabsList)) {
    $tabIndex = 0;

    foreach ($tabsList as $tabsIName) {
        $tabMeta = realpath($tabsPath . '/' . $tabsIName . '/' . $tabsIName . 'Class.php');

        if ($tabMeta and file_exists($tabMeta)) {
            include_once $tabMeta;

            $className = ucfirst($tabsIName) . 'Class';

            $tabs[$tabsIName] = new $className($request);

            if (array_key_exists('tabName', $request)) {
                if ($request['tabName'] === $tabsIName) {
                    setcookie('webfxtab_documentPane', $tabIndex);
                }
            }
        }

        $tabIndex++;
    }
}

uasort($tabs, function ($left, $right) {
    if (intval($left->orderPosition) === intval($right->orderPosition)) {
        return 0;
    }

    return (intval($left->orderPosition) < intval($right->orderPosition)) ? -1 : 1;
});

if (!array_key_exists('tabName', $request)) {
    $request['tabName'] = array_shift(array_keys($tabs));

    setcookie('webfxtab_documentPane', 0);
}

$cabinetPath = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__) . '/../../'))), '/');
$modulePath = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__)))), '/');

include_once MODX_MANAGER_PATH . 'includes/header.inc.php';

?>

<link rel="stylesheet" type="text/css" href="/admin/media/style/default/css/styles.min.css?v=<?= time(); ?>" />
<link rel="stylesheet" type="text/css" href="<?= $modulePath . '/assets/css/style.css'; ?>?v=<?= time(); ?>" />
<link rel="stylesheet" type="text/css" href="<?= $modulePath . '/assets/libs/bootstrap/bootstrap-grid.min.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?= $modulePath . '/assets/libs/bootstrap/bootstrap-spacing.min.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?= $cabinetPath . '/app/assets/libs/fontawesome-5.15.4/css/all.min.css'; ?>?v=<?= time(); ?>" />
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<h1>
    <i class="fa fa-cog"></i>LK Admin
</h1>

<form name="settings" method="post" id="mutate" class="modx-evo-lk-admin">
    <div class="sectionBody" id="settingsPane">
        <div class="tab-pane" id="documentPane">
            <script type="text/javascript">
                var tpSettings = new WebFXTabPane(document.getElementById('documentPane'), <?= ($modx->getConfig('remember_last_tab') == 1 ? 'true' : 'false') ?>);
            </script>

<?php foreach ($tabs as $name => $tab) : ?>
            <div class="tab-page" id="tab_<?= $name ?>">
                <h2 class="tab"><?= $tab->title ?></h2>
                <script type="text/javascript">
                    tpSettings.addTabPage(document.getElementById('tab_<?= $name ?>'));
                </script>
            </div>
    <?php $tabIndex++; ?>
<?php endforeach; ?>
        </div>
    </div>
</form>

<script>
    let a = <?= (array_key_exists('a', $request) && !is_null($request['a']) && !empty($request['a'])) ? "'{$request['a']}'" : "null"; ?>;
    let id = <?= (array_key_exists('id', $request) && !is_null($request['id']) && !empty($request['id'])) ? "'{$request['id']}'" : "null"; ?>;
    let startTabName = <?= (array_key_exists('tabName', $request) && !is_null($request['tabName']) && !empty($request['tabName'])) ? "'{$request['tabName']}'" : "null"; ?>;
    let startMethodName = <?= (array_key_exists('method', $request) && !is_null($request['method']) && !empty($request['method'])) ? "'{$request['method']}'" : "'index'"; ?>;
    let apiUrl = '<?= $modulePath; ?>/api.php';
</script>

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?= $modulePath . '/assets/js/logger.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/overPriceModule.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/buffer.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/module.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/tabs/ordersTabScripts.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/tabs/usersTabScripts.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/tabs/flightsTabScripts.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/js/script.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>

<?php include_once MODX_MANAGER_PATH . 'includes/footer.inc.php'; ?>
