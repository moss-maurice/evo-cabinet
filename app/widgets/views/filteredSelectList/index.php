<?php global $modx; ?>

<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\widgets\AdminFilteredSelectListWidget; ?>

<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot(AdminFilteredSelectListWidget::WIDGET_THEMES[$theme]); ?>?v=<?= time(); ?>">
<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot(AdminFilteredSelectListWidget::WIDGET_PATH . '/assets/styles/style.css'); ?>?v=<?= time(); ?>">

<select name="<?= $name; ?>" id="<?= $id; ?>"<?= (!empty($class) ? ' class="' . $class . '"' : ''); ?>></select>

<script type="text/javascript" src="<?= App::getPublicWebRoot('/assets/libs/selectize-0.13.3/js/standalone/selectize.min.js'); ?>?v=<?= time(); ?>"></script>

<script>
    jQuery(document).ready(function () {
        // Инициализация виджета
        var selectElement = jQuery('#<?= $id; ?>').selectize({
            theme: 'links',
            maxItems: null,
            valueField: '<?= $valueField; ?>',
            searchField: '<?= $searchField; ?>',
            options: <?= $options; ?>,
            plugins: ['remove_button'],
            render: {
                option: function(data, escape) {
                    return '<?= $optionTmpl; ?>';
                },
                item: function(data, escape) {
                    return '<?= $itemTmpl; ?>';
                }
            },
            create: false,
        });

        var selectItems = <?= $selected; ?>;

        // Наполнение виджета предвыбранными элементами
        if (selectItems.length > 0) {
            for (var i = 0; i < selectItems.length; i++) {
                selectElement[0].selectize.addItem(parseInt(selectItems[i]));
            }
        }
    });
</script>
