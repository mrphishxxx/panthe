<?php if ( ! defined('NC')) exit ?>
<?/*------------------------------------------------------------------------*/?>

<? example('nc-label') ?>
<?=$nc_core->ui->label('white')->white() ?>

<?=$nc_core->ui->label('lighten')->lighten() ?>

<?=$nc_core->ui->label('light')->light() ?>

<?=$nc_core->ui->label('grey')->grey() ?>

<?=$nc_core->ui->label('dark')->dark() ?>

<?=$nc_core->ui->label('darken')->darken() ?>

<?=$nc_core->ui->label('black')->black() ?>

<?=$nc_core->ui->label('blue')->blue() ?>

<?=$nc_core->ui->label('red')->red() ?>

<?=$nc_core->ui->label('green')->green() ?>

<?=$nc_core->ui->label('yellow')->yellow() ?>



<? example('nc-label nc--rounded') ?>

<?=$nc_core->ui->label('white')->white()->rounded() ?>

<?=$nc_core->ui->label('lighten')->lighten()->rounded() ?>

<?=$nc_core->ui->label('light')->light()->rounded() ?>

<?=$nc_core->ui->label('grey')->grey()->rounded() ?>

<?=$nc_core->ui->label('dark')->dark()->rounded() ?>

<?=$nc_core->ui->label('darken')->darken()->rounded() ?>

<?=$nc_core->ui->label('black')->black()->rounded() ?>

<?=$nc_core->ui->label('blue')->blue()->rounded() ?>

<?=$nc_core->ui->label('red')->red()->rounded() ?>

<?=$nc_core->ui->label('green')->green()->rounded() ?>

<?=$nc_core->ui->label('yellow')->yellow()->rounded() ?>


<? example('nc-label | дополнительно') ?>

<?=$nc_core->ui->label('link label')->blue()->href('#') ?>


<? $label = $nc_core->ui->label->yellow()->href('#')->rounded() ?>

<?=$label->icon('edit') ?>

<?=$label->icon('field-string') ?>

<?=$label->icon('field-text') ?>

<?=$label->icon('field-int') ?>

<?=$label->icon('field-bool') ?>


<?=$nc_core->ui->label('icon link label')->lighten()->icon('sql-console') ?>

