<?php if ( ! defined('NC')) exit ?>
<?/*------------------------------------------------------------------------*/?>

<? example('nc-btn') ?>

<?=$nc_core->ui->btn('#', 'По умолчанию') ?>

<?=$nc_core->ui->btn('#', 'Синяя')->blue() ?>

<?=$nc_core->ui->btn('#', 'Красная')->red() ?>

<?=$nc_core->ui->btn('#', 'Зеленая')->green() ?>

<?=$nc_core->ui->btn('#', 'Желтая')->yellow() ?>



<? example('nc-btn | b/w') ?>

<?=$nc_core->ui->btn('#', 'white')->white() ?>

<?=$nc_core->ui->btn('#', 'lighten')->lighten() ?>

<?=$nc_core->ui->btn('#', 'light')->light() ?>

<?=$nc_core->ui->btn('#', 'grey')->grey() ?>

<?=$nc_core->ui->btn('#', 'dark')->dark() ?>

<?=$nc_core->ui->btn('#', 'darken')->darken() ?>

<?=$nc_core->ui->btn('#', 'black')->black() ?>



<? example('nc-btn nc--large') ?>

<?=$nc_core->ui->btn('#', 'По умолчанию')->large() ?>

<?=$nc_core->ui->btn('#', 'Синяя')->blue()->large() ?>

<?=$nc_core->ui->btn('#', 'Красная')->red()->large() ?>

<?=$nc_core->ui->btn('#', 'Зеленая')->green()->large() ?>

<?=$nc_core->ui->btn('#', 'Желтая')->yellow()->large() ?>



<? example('nc-btn nc--small') ?>

<?=$nc_core->ui->btn('#', 'По умолчанию')->small() ?>

<?=$nc_core->ui->btn('#', 'Синяя')->blue()->small() ?>

<?=$nc_core->ui->btn('#', 'Красная')->red()->small() ?>

<?=$nc_core->ui->btn('#', 'Зеленая')->green()->small() ?>

<?=$nc_core->ui->btn('#', 'Желтая')->yellow()->small() ?>



<? example('nc-btn nc--bordered | с иконками') ?>

<?=$nc_core->ui->btn('#', 'Сайт')->bordered()->blue()->icon('site') ?>

<?=$nc_core->ui->btn('#', 'Сайт')->red() ?>

<?=$nc_core->ui->btn('#', 'Сообщение')->bordered()->green()->icon('user') ?>

<?=$nc_core->ui->btn('#', 'Удалить')->red()->bordered()->small()->icon('remove') ?>


