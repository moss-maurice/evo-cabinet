<?php if (!is_null($message)) : ?>
    <h4 class="text-left alert alert-danger text-center p-4">
        <i class="fas fa-exclamation-triangle"></i> <?= $message; ?>
    </h4>
<?php endif; ?>
