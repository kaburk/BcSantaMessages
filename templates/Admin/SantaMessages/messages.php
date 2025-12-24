<?php
/**
 * @var \BaserCore\View\BcAdminAppView $this
 * @var \Cake\ORM\ResultSet $messages
 */
$this->BcListTable->setColumnNumber(9);
?>

<h2 class="bca-main__heading"><?= __d('baser_core', '生成履歴') ?></h2>

<div class="bca-data-list__top">
  <div class="bca-data-list__sub">
    <?php $this->BcBaser->element('pagination') ?>
  </div>
</div>

<table class="list-table bca-table-listup" id="ListTable">
  <thead class="bca-table-listup__thead">
    <tr>
      <th class="list-tool bca-table-listup__thead-th  bca-table-listup__thead-th--select">
        <?php echo $this->Paginator->sort('id', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', 'No') . '"></i>' . __d('baser_core', 'No'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', 'No') . '"></i>' . __d('baser_core', 'No')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?php echo $this->Paginator->sort('child_name', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', 'なまえ') . '"></i>' . __d('baser_core', 'なまえ'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', 'なまえ') . '"></i>' . __d('baser_core', 'なまえ')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?php echo $this->Paginator->sort('age', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', 'ねんれい') . '"></i>' . __d('baser_core', 'ねんれい'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', 'ねんれい') . '"></i>' . __d('baser_core', 'ねんれい')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?php echo $this->Paginator->sort('tone', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', 'くちょう') . '"></i>' . __d('baser_core', 'くちょう'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', 'くちょう') . '"></i>' . __d('baser_core', 'くちょう')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?php echo $this->Paginator->sort('provider', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', 'プロバイダ') . '"></i>' . __d('baser_core', 'プロバイダ'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', 'プロバイダ') . '"></i>' . __d('baser_core', 'プロバイダ')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?php echo $this->Paginator->sort('model', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', 'モデル') . '"></i>' . __d('baser_core', 'モデル'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', 'モデル') . '"></i>' . __d('baser_core', 'モデル')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?php echo $this->Paginator->sort('created', [
          'asc' => '<i class="bca-icon--asc" title="' . __d('baser_core', '日時') . '"></i>' . __d('baser_core', '日時'),
          'desc' => '<i class="bca-icon--desc" title="' . __d('baser_core', '日時') . '"></i>' . __d('baser_core', '日時')
        ], ['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?= __d('baser_core', '本文（先頭100文字）') ?>
      </th>
      <th class="bca-table-listup__thead-th">
        <?= __d('baser_core', '操作') ?>
      </th>
    </tr>
  </thead>
  <tbody>
    <?php if ($messages->count()): ?>
      <?php foreach ($messages as $m): ?>
        <tr>
          <td class="bca-table-listup__tbody-td"><?= h($m->id) ?></td>
          <td class="bca-table-listup__tbody-td"><?= h($m->child_name) ?></td>
          <td class="bca-table-listup__tbody-td"><?= h($m->age) ?></td>
          <td class="bca-table-listup__tbody-td"><?= h($m->tone) ?></td>
          <td class="bca-table-listup__tbody-td"><?= h($m->provider) ?></td>
          <td class="bca-table-listup__tbody-td"><?= h($m->model) ?></td>
          <td class="bca-table-listup__tbody-td" style="white-space: nowrap;"><?= h($m->created) ?></td>
          <td class="bca-table-listup__tbody-td"><?= h(mb_substr((string)$m->message, 0, 100)) ?><?= (mb_strlen((string)$m->message) > 100) ? '…' : '' ?></td>
          <td class="row-tools bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
            <?php $this->BcBaser->link('', ['plugin' => 'BcSantaMessage', 'controller' => 'SantaMessages', 'action' => 'view', 'prefix' => 'Admin', $m->id], ['title' => __d('baser_core', '詳細'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']) ?>
            <?= $this->BcAdminForm->postLink(
              '',
              ['plugin' => 'BcSantaMessage', 'controller' => 'SantaMessages', 'action' => 'delete', 'prefix' => 'Admin', $m->id],
              [
                'confirm' => __d('baser_core', '{0} を本当に削除してもいいですか？', empty($m->child_name) ? $m->id : $m->child_name),
                'title' => __d('baser_core', '削除'),
                'class' => 'btn-delete bca-btn-icon',
                'data-bca-btn-type' => 'delete',
                'data-bca-btn-size' => 'lg'
              ]
            ) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="<?= $this->BcListTable->getColumnNumber() ?>">
          <p class="no-data"><?= __d('baser_core', 'データが見つかりませんでした。') ?></p>
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<div class="bca-data-list__bottom">
  <div class="bca-data-list__sub">
    <?php $this->BcBaser->element('pagination') ?>
    <?php $this->BcBaser->element('list_num') ?>
  </div>
</div>

<?= $this->fetch('postLink') ?>
