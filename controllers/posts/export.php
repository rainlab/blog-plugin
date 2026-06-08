<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('rainlab/blog/posts') ?>"><?= e(__("Blog")) ?></a></li>
        <li><?= e(trans($this->pageTitle)) ?></li>
    </ul>
<?php Block::endPut() ?>

<?= Form::open(['class' => 'd-flex flex-column h-100']) ?>

    <div class="flex-grow-1">
        <?= $this->exportRender() ?>
    </div>

    <div class="form-buttons">
        <div data-control="loader-container">
            <?= Ui::popupButton(
                label: __("Export Posts"),
                handler: 'onExportLoadForm',
                icon: 'icon-cloud-download',
                primary: true,
                dataKeyboard: 'false'
            ) ?>
        </div>
    </div>

<?= Form::close() ?>
