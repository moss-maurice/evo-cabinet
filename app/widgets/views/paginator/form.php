<?php use mmaurice\cabinet\core\App; ?>

<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/widgets/views/paginator/assets/styles/style.css'); ?>?v=<?= time(); ?>">
<script src="<?= App::getPublicWebRoot('/widgets/views/paginator/assets/scripts/script.js'); ?>?v=<?= time(); ?>" type="text/javascript"></script>

<?php $content = ''; ?>

<?php if ($page > 0) : ?>
    <?php if ($page > 1) : ?>
        <?php $prevPage = ((($page - 1) >= 1) ? ($page - 1) : $page); ?>
        <?php $content .= $firstButton; ?>
        <?php $content .= str_replace('[prevPage]', $prevPage, $prevButton); ?>
    <?php endif; ?>

    <?php if ($page > ($range + 1)) : ?>
        <?= $dotsButton; ?>
    <?php endif; ?>

    <?php for ($currentPage = ($page - $range); $currentPage < $page; $currentPage++) : ?>
        <?php if ($currentPage >= 1) : ?>
            <?php $content .= str_replace('[page]', $currentPage, $button); ?>
        <?php endif; ?>
    <?php endfor; ?>

    <?php $content .= str_replace('[currentPage]', $page, $activeButton); ?>

    <?php for ($currentPage = ($page + 1); $currentPage < ($page + $range + 1); $currentPage++) : ?>
        <?php if ($currentPage <= $pages) : ?>
            <?php $content .= str_replace('[page]', $currentPage, $button); ?>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < ($pages - $range - 1)) : ?>
        <?php $content .= $dotsButton; ?>
    <?php endif; ?>

    <?php if ($page < $pages) : ?>
        <?php $nextPage = (($page + 1) <= $pages) ? ($page + 1) : $page; ?>
        <?php $content .= str_replace('[nextPage]', $nextPage, $nextButton); ?>
        <?php $content .= str_replace('[pages]', $pages, $lastButton); ?>
    <?php endif; ?>
<?php endif; ?>

<?= str_replace('[content]', $content, $container); ?>
