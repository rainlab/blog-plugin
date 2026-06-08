<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('rainlab/blog/posts') ?>"><?= e(__("Blog")) ?></a></li>
        <li><?= e(trans($this->pageTitle)) ?></li>
    </ul>
<?php Block::endPut() ?>

<?= Form::open(['class' => 'layout']) ?>

    <div class="layout-row">
        <?= $this->exportRender() ?>
    </div>

    <div class="form-buttons">
        <div class="loading-indicator-container">
            <button
                type="submit"
                data-control="popup"
                data-handler="onExportLoadForm"
                data-keyboard="false"
                class="btn btn-primary">
                <?= e(__("Export Posts")) ?>
            </button>
        </div>
    </div>

<?= Form::close() ?>
