<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('rainlab/blog/posts') ?>"><?= e(__("Blog")) ?></a></li>
        <li><?= e(trans($this->pageTitle)) ?></li>
    </ul>
<?php Block::endPut() ?>

<?= Form::open(['class' => 'd-flex flex-column h-100']) ?>

    <div class="flex-grow-1">
        <?= $this->importRender() ?>
    </div>

    <div class="form-buttons">
        <?= Ui::popupButton(
            label: __("Import Posts"),
            handler: 'onImportLoadForm',
            icon: 'icon-cloud-upload',
            primary: true,
            dataKeyboard: 'false'
        ) ?>
    </div>

<?= Form::close() ?>
