<?php
/**
 * @var \BaserCore\View\BcFrontAppView $this
 */
$this->BcBaser->setTitle($pageTitle ?? 'サンタからのメッセージ');
?>

<h1>サンタからのメッセージ自動生成</h1>

<?= $this->element('BcSantaMessage.santa_message_form'); ?>

<div id="santa-result" style="margin-top:16px; white-space:pre-wrap;"></div>

<?= $this->BcBaser->js('BcSantaMessage.santa_message', ['defer' => true]); ?>
