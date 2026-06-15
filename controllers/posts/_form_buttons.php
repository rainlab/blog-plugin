<?php $pageUrl = $pageUrl ?? null; ?>
<?php if (!$this->formGetModel()->exists): ?>
    <?= Ui::ajaxButton(
        label: __("Save"),
        handler: 'onSave',
        icon: 'icon-save-cloud',
        primary: true,
        hotkey: ['ctrl+s', 'cmd+s'],
        dataRequestData: "redirect: 0"
    ) ?>
<?php else: ?>
    <?= Ui::ajaxButton(
        label: __("Save"),
        handler: 'onSave',
        icon: 'icon-save-cloud',
        primary: true,
        hotkey: ['ctrl+s', 'cmd+s'],
        dataRequestData: "redirect: 0"
    ) ?>

    <?= Ui::ajaxButton(
        label: __("Save & Close"),
        handler: 'onSave',
        icon: 'icon-keyboard-return',
        hotkey: ['ctrl+enter', 'cmd+enter'],
        dataBrowserRedirectBack: true,
        dataRequestData: "close: 1"
    ) ?>

    <?php if (!empty($pageUrl)): ?>
        <?= Ui::button(
            label: __("Preview"),
            href: Url::to($pageUrl),
            icon: 'icon-crosshairs',
            target: '_blank'
        ) ?>
    <?php endif ?>

    <?php if ($this->formCheckPermission('modelDelete')): ?>
        <?= Ui::ajaxButton(
            label: __("Delete"),
            handler: 'onDelete',
            icon: 'icon-delete',
            hotkey: ['shift+option+d'],
            class: 'pull-right',
            dataRequestConfirm: __("Delete this post?")
        ) ?>
    <?php endif ?>
<?php endif ?>
