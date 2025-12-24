<?php
$csrf = null;
if (isset($this->request)) {
    $csrf = $this->request->getAttribute('csrfToken');
}
?>

<?= $this->BcForm->create(null, ['id' => 'santa-form', 'data-autofill' => '1']) ?>

<?= $this->BcForm->label('child_name', __d('baser_core', 'なまえ（必須）')) ?><br>
<?= $this->BcForm->control('child_name', [
  'type' => 'text',
  'label' => false,
  'required' => true,
  'maxlength' => 30,
  'id' => 'child_name'
]) ?>
<br><br>

<?= $this->BcForm->label('age', __d('baser_core', 'ねんれい')) ?><br>
<?= $this->BcForm->control('age', [
  'type' => 'number',
  'label' => false,
  'min' => 1,
  'max' => 120,
  'id' => 'age'
]) ?>
<br><br>

<?= $this->BcForm->label('good_thing', __d('baser_core', 'ことし がんばったこと')) ?><br>
<?= $this->BcForm->control('good_thing', [
  'type' => 'text',
  'label' => false,
  'maxlength' => 80,
  'id' => 'good_thing'
]) ?>
<br><br>

<?= $this->BcForm->label('gift_hint', __d('baser_core', 'ほしいもののヒント')) ?><br>
<?= $this->BcForm->control('gift_hint', [
  'type' => 'text',
  'label' => false,
  'maxlength' => 80,
  'id' => 'gift_hint'
]) ?>
<br><br>

<?= $this->BcForm->label('tone', __d('baser_core', 'くちょう')) ?><br>
<?= $this->BcForm->control('tone', [
  'type' => 'select',
  'label' => false,
  'options' => [
    '優しい' => __d('baser_core', '優しい'),
    '面白い' => __d('baser_core', '面白い'),
    'ちょっと厳しめだけど愛がある' => __d('baser_core', 'ちょっと厳しめだけど愛がある'),
  ],
  'default' => '優しい'
]) ?>
<br><br>

<?= $this->BcForm->button(__d('baser_core', 'メッセージを作る'), ['type' => 'submit']) ?>
<?= $this->BcForm->end() ?>
