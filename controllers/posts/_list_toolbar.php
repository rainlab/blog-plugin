<div data-control="toolbar">
    <a
        href="<?= Backend::url('rainlab/blog/posts/create') ?>"
        class="btn btn-primary oc-icon-plus">
        <?= e(__("New Post")) ?>
    </a>
    <button
        class="btn btn-default oc-icon-trash-o"
        disabled="disabled"
        onclick="$(this).data('request-data', {
            checked: $('.control-list').listWidget('getChecked')
        })"
        data-request="onDelete"
        data-request-confirm="<?= e(__("Are you sure?")) ?>"
        data-trigger-action="enable"
        data-trigger=".control-list input[type=checkbox]"
        data-trigger-condition="checked"
        data-request-success="$(this).prop('disabled', true)"
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')) ?>
    </button>

    <?php if ($this->user->hasAnyAccess(['rainlab.blog.access_import_export'])): ?>
        <div class="btn-group">
            <a
                href="<?= Backend::url('rainlab/blog/posts/export') ?>"
                class="btn btn-default oc-icon-download">
                <?= e(__("Export Posts")) ?>
            </a>
            <a
                href="<?= Backend::url('rainlab/blog/posts/import') ?>"
                class="btn btn-default oc-icon-upload">
                <?= e(__("Import Posts")) ?>
            </a>
        </div>
    <?php endif ?>
</div>
