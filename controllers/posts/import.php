<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('rainlab/blog/posts') ?>"><?= e(__("Blog")) ?></a></li>
        <li><?= e(trans($this->pageTitle)) ?></li>
    </ul>
<?php Block::endPut() ?>

<?= Form::open(['class' => 'layout']) ?>

    <div class="layout-row">
        <?= $this->importRender() ?>
    </div>

    <div class="form-buttons">
        <button
            type="submit"
            data-control="popup"
            data-handler="onImportLoadForm"
            data-keyboard="false"
            class="btn btn-primary">
            <?= e(__("Import Posts")) ?>
        </button>
    </div>

<?= Form::close() ?>
