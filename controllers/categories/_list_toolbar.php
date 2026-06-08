<div data-control="toolbar">
    <?= Ui::button(
        label: __("New Category"),
        href: Backend::url('rainlab/blog/categories/create'),
        icon: 'icon-plus',
        primary: true
    ) ?>

    <div class="toolbar-divider"></div>

    <?= Ui::ajaxButton(
        label: __("Delete"),
        handler: 'onDelete',
        icon: 'icon-delete',
        secondary: true,
        dataRequestConfirm: __("Are you sure?"),
        dataListCheckedTrigger: true,
        dataListCheckedRequest: true,
        disabled: true
    ) ?>
</div>
