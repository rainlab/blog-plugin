<?php
    $isCreate = $this->formGetContext() == 'create';
    $pageUrl = isset($pageUrl) ? $pageUrl : null;
?>
<div class="form-buttons">
    <div data-control="loader-container" class="control-loader-container">

        <!-- Save -->
        <?= Ui::ajaxButton(
            label: __("Save"),
            handler: 'onSave',
            icon: 'icon-check',
            primary: true,
            hotkey: ['ctrl+s', 'cmd+s'],
            dataRequestData: $isCreate ? null : "redirect: 0",
            dataRequestBeforeUpdate: "\$(this).trigger('unchange.oc.changeMonitor')",
            dataRequestMessage: __("Saving...")
        ) ?>

        <?php if (!$isCreate): ?>
            <!-- Save and Close -->
            <?= Ui::ajaxButton(
                label: __("Save & Close"),
                handler: 'onSave',
                icon: 'icon-check',
                primary: true,
                dataRequestBeforeUpdate: "\$(this).trigger('unchange.oc.changeMonitor')",
                dataRequestMessage: __("Saving...")
            ) ?>
        <?php endif ?>

        <!-- Cancel -->
        <?= Ui::button(
            label: __("Cancel"),
            href: Backend::url('rainlab/blog/posts'),
            icon: 'icon-arrow-left',
            primary: true
        ) ?>

        <?php if (!empty($pageUrl)): ?>
            <!-- Preview -->
            <?= Ui::button(
                label: __("Preview"),
                href: Url::to($pageUrl),
                icon: 'icon-crosshairs',
                primary: true,
                target: '_blank',
                dataControl: 'preview-button'
            ) ?>
        <?php endif ?>

        <?php if (!$isCreate): ?>
            <!-- Delete -->
            <?= Ui::iconButton(
                label: __("Delete"),
                icon: 'oc-icon-trash-o',
                handler: 'onDelete',
                dataRequestConfirm: __("Delete this post?"),
                dataControl: 'delete-button'
            ) ?>
        <?php endif ?>
    </div>
</div>
