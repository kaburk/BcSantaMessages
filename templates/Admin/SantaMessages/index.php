<?php
/**
 * @var \BaserCore\View\BcAdminAppView $this
 * @var \BcSantaMessage\Model\Entity\SantaMessageSetting $setting
 */
?>

<h1><?= __d('baser_core', 'BcSantaMessage 設定') ?></h1>

<?php // 管理画面用のスコープ付きCSSを読み込み ?>
<?= $this->BcBaser->css('BcSantaMessage.admin') ?>

<div class="submit bca-actions">
  <div class="bca-actions__main">
    <?= $this->BcBaser->link(
      __d('baser_core', '生成履歴を見る'),
      ['plugin' => 'BcSantaMessage', 'controller' => 'SantaMessages', 'action' => 'messages', 'prefix' => 'Admin'],
      [
        'class' => 'button bca-btn bca-actions__item',
        'data-bca-btn-type' => 'back-to-list'
      ]
    ) ?>
  </div>
</div>

<?= $this->BcAdminForm->create($setting, ['id' => 'bc-santa-message-admin']) ?>

<div class="bca-section" data-bca-section-type="form-group">
  <h2 class="bca-main__heading"><?= __d('baser_core', '基本') ?></h2>
  <table class="bca-form-table">
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('enabled', __d('baser_core', '機能を有効にする')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('enabled', [
          'type' => 'checkbox',
          'label' => false,
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('provider', __d('baser_core', '生成AIプロバイダ')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('provider', [
          'type' => 'select',
          'label' => false,
          'options' => ['gemini' => 'Gemini', 'ollama' => 'Ollama（ローカル）'],
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('max_tokens', __d('baser_core', '最大出力トークン（目安）')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('max_tokens', [
          'type' => 'number',
          'label' => false,
          'min' => 50,
          'max' => 4000,
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('temperature', __d('baser_core', 'temperature（0〜2）')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('temperature', [
          'type' => 'number',
          'label' => false,
          'step' => '0.01',
          'min' => 0,
          'max' => 2,
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('rate_limit_seconds', __d('baser_core', 'レート制限（秒）0で無制限')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('rate_limit_seconds', [
          'type' => 'number',
          'label' => false,
          'min' => 0,
          'max' => 3600,
        ]) ?>
      </td>
    </tr>
  </table>
</div>

<div class="bca-section" data-bca-section-type="form-group">
  <h2 class="bca-main__heading"><?= __d('baser_core', 'Gemini設定') ?></h2>
  <table class="bca-form-table">
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('gemini_api_key', __d('baser_core', 'Gemini APIキー')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('gemini_api_key', [
          'type' => 'password',
          'label' => false,
          'autocomplete' => 'new-password',
          'size' => 80,
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('gemini_model', __d('baser_core', 'Gemini モデル')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('gemini_model', [
          'type' => 'text',
          'label' => false,
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('gemini_endpoint', __d('baser_core', 'Gemini エンドポイント')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('gemini_endpoint', [
          'type' => 'text',
          'label' => false,
          'size' => 80,
        ]) ?>
      </td>
    </tr>
  </table>
</div>

<div class="bca-section" data-bca-section-type="form-group">
  <h2 class="bca-main__heading"><?= __d('baser_core', 'Ollama設定（ローカル）') ?></h2>
  <table class="bca-form-table">
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('ollama_base_url', __d('baser_core', 'Ollama Base URL')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('ollama_base_url', [
          'type' => 'text',
          'label' => false,
          'size' => 80,
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('ollama_model', __d('baser_core', 'Ollama モデル')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('ollama_model', [
          'type' => 'text',
          'label' => false,
        ]) ?>
      </td>
    </tr>
  </table>
</div>

<div class="submit bca-actions">
  <div class="bca-actions__main">
    <?= $this->BcAdminForm->button(__d('baser_core', '保存'), [
      'div' => false,
      'class' => 'button bca-btn bca-actions__item',
      'data-bca-btn-type' => 'save',
      'data-bca-btn-size' => 'lg',
      'data-bca-btn-width' => 'lg',
      'id' => 'BtnSave'
    ]) ?>
  </div>
</div>

<?= $this->BcAdminForm->end() ?>


<hr>
<h2><?= __d('baser_core', 'テスト生成') ?></h2>
<p><?= __d('baser_core', '※ 現在保存されている設定で、サンプル入力から生成します（保存はしません）。') ?></p>

<?= $this->BcAdminForm->create(null, ['id' => 'bc-santa-test-form']) ?>

<div class="bca-section" data-bca-section-type="form-group">
  <table class="bca-form-table">
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('test.child_name', __d('baser_core', 'なまえ（必須）')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('test.child_name', [
          'type' => 'text',
          'label' => false,
          'required' => true,
          'maxlength' => 30,
          'value' => __d('baser_core', 'たろう'),
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('test.age', __d('baser_core', 'ねんれい')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('test.age', [
          'type' => 'number',
          'label' => false,
          'min' => 1,
          'max' => 120,
          'value' => 7
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('test.good_thing', __d('baser_core', 'ことし がんばったこと')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('test.good_thing', [
          'type' => 'text',
          'label' => false,
          'size' => 60,
          'maxlength' => 80,
          'value' => __d('baser_core', '毎日早起きして学校に行った'),
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('test.gift_hint', __d('baser_core', 'ほしいもののヒント')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('test.gift_hint', [
          'type' => 'text',
          'label' => false,
          'size' => 60,
          'maxlength' => 80,
          'value' => __d('baser_core', 'ブロックのおもちゃ'),
        ]) ?>
      </td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= $this->BcAdminForm->label('test.tone', __d('baser_core', 'くちょう')) ?></th>
      <td class="bca-form-table__input">
        <?= $this->BcAdminForm->control('test.tone', [
          'type' => 'select',
          'label' => false,
          'options' => [
            '優しい' => __d('baser_core', '優しい'),
            '面白い' => __d('baser_core', '面白い'),
            'ちょっと厳しめだけど愛がある' => __d('baser_core', 'ちょっと厳しめだけど愛がある')
          ],
          'default' => '優しい'
        ]) ?>
      </td>
    </tr>
  </table>

  <div class="bca-actions">
    <?= $this->BcAdminForm->button(__d('baser_core', 'テスト生成する'), [
      'type' => 'submit',
      'class' => 'bca-btn',
      'data-bca-btn-type' => 'submit',
      'data-bca-btn-size' => 'lg'
    ]) ?>
  </div>
</div>

<?= $this->BcAdminForm->end() ?>

<div id="bc-santa-test-result" style="margin-top:12px; white-space:pre-wrap;"></div>

<script>
function getCsrfTokenForAdminTest(form) {
  // meta優先（Cake推奨）、なければフォームhidden
  const meta = document.querySelector('meta[name="csrfToken"]');
  if (meta && meta.content) return meta.content;

  const input = form.querySelector('input[name="_csrfToken"]');
  return input ? input.value : '';
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('bc-santa-test-form');
  const result = document.getElementById('bc-santa-test-result');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    result.textContent = '生成中…';

    const formData = new FormData(form);
    const csrfToken = getCsrfTokenForAdminTest(form);

    try {
      const res = await fetch('/bc-santa-message/admin/santa-message/test-generate', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          ...(csrfToken ? {'X-CSRF-Token': csrfToken} : {})
        }
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok || !data.ok) {
        throw new Error(data.message || `エラーが発生しました (${res.status})`);
      }
      result.textContent = data.message;
    } catch (err) {
      result.textContent = '失敗しました: ' + (err?.message || err);
    }
  });
});
</script>
