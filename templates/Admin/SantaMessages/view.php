<?php
/**
 * @var \BaserCore\View\BcAdminAppView $this
 * @var \BcSantaMessage\Model\Entity\SantaMessage $message
 */
?>

<h2><?= __d('baser_core', '生成メッセージ詳細') ?></h2>

<div class="bca-section" data-bca-section-type="form-group">
  <table class="bca-form-table">
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', '日時') ?></th>
      <td class="bca-form-table__input"><?= h($message->created) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'なまえ') ?></th>
      <td class="bca-form-table__input"><?= h($message->child_name) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'ねんれい') ?></th>
      <td class="bca-form-table__input"><?= h($message->age) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'くちょう') ?></th>
      <td class="bca-form-table__input"><?= h($message->tone) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'がんばったこと') ?></th>
      <td class="bca-form-table__input"><?= h($message->good_thing) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'ほしいもののヒント') ?></th>
      <td class="bca-form-table__input"><?= h($message->gift_hint) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'プロバイダ') ?></th>
      <td class="bca-form-table__input"><?= h($message->provider) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', 'モデル') ?></th>
      <td class="bca-form-table__input"><?= h($message->model) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label">IP</th>
      <td class="bca-form-table__input"><?= h($message->client_ip) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label">User-Agent</th>
      <td class="bca-form-table__input"><?= h($message->user_agent) ?></td>
    </tr>
    <tr>
      <th class="bca-form-table__label"><?= __d('baser_core', '本文') ?></th>
      <td class="bca-form-table__input"><pre style="white-space:pre-wrap; margin:0;"><?= h($message->message) ?></pre></td>
    </tr>
  </table>

  <div class="submit bca-actions">
    <div class="bca-actions__main">
      <?= $this->BcBaser->link(
        __d('baser_core', '一覧に戻る'),
        ['plugin' => 'BcSantaMessage', 'controller' => 'SantaMessages', 'action' => 'messages', 'prefix' => 'Admin'],
        [
          'class' => 'button bca-btn bca-actions__item',
          'data-bca-btn-type' => 'back-to-list'
        ]
      ) ?>
    </div>
    <div class="bca-actions__sub">
      <?= $this->BcAdminForm->postLink(
        __d('baser_core', '削除'),
        ['plugin' => 'BcSantaMessage', 'controller' => 'SantaMessages', 'action' => 'delete', 'prefix' => 'Admin', $message->id],
        [
          'block' => true,
          'confirm' => __d('baser_core', '本当に削除してもよろしいですか？'),
          'class' => 'bca-btn bca-actions__item',
          'data-bca-btn-type' => 'delete',
          'data-bca-btn-size' => 'sm',
          'data-bca-btn-color' => 'danger'
        ]
      ) ?>
    </div>
  </div>
</div>
