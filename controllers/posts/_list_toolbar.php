<div data-control="toolbar">
    <?= Ui::button(
        label: __("New Post"),
        href: Backend::url('rainlab/blog/posts/create'),
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

    <?php if ($this->user->hasAnyAccess(['rainlab.blog.access_import_export'])): ?>
        <?php Ui::dropdownButton(
            title: __("More Actions"),
            icon: 'icon-ellipsis-v',
            secondary: true,
            caret: false,
            class: 'btn-circle'
        )->slot() ?>
            <?= Ui::dropdownItem(
                label: __("Import Posts"),
                href: Backend::url('rainlab/blog/posts/import'),
                icon: 'icon-upload'
            ) ?>
            <?= Ui::dropdownItem(
                label: __("Export Posts"),
                href: Backend::url('rainlab/blog/posts/export'),
                icon: 'icon-download'
            ) ?>
        <?= Ui::end() ?>
    <?php endif ?>
</div>
