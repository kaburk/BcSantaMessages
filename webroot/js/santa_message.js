function getCsrfToken() {
  // baser/Cake: meta優先 → hidden fallback
  const meta = document.querySelector('meta[name="csrfToken"]');
  if (meta?.content) return meta.content;

  const input = document.querySelector('input[name="_csrfToken"]');
  return input?.value || '';
}

function autofillSample(form) {
  const names = ['たろう', 'はなこ', 'ゆうと', 'さくら', 'そうた', 'あおい', 'りく', 'ゆい', 'はると', 'めい'];
  const ages = [5, 6, 7, 8, 9, 10];
  const goodThings = [
    '毎日宿題をした',
    'お手伝いをがんばった',
    '早起きをした',
    '野菜を食べた',
    '本をたくさん読んだ',
    '妹や弟の面倒を見た',
    'お片付けをした',
    '歯磨きを忘れなかった'
  ];
  const giftHints = [
    'ゲーム',
    'レゴブロック',
    '絵本',
    '自転車',
    'ぬいぐるみ',
    'サッカーボール',
    'お絵かきセット',
    'ロボット'
  ];

  const random = (arr) => arr[Math.floor(Math.random() * arr.length)];

  const child = form.querySelector('#child_name');
  const age = form.querySelector('#age');
  const good = form.querySelector('#good_thing');
  const gift = form.querySelector('#gift_hint');

  // 既に入力がある場合は上書きしない
  if (child && !child.value) child.value = random(names);
  if (age && !age.value) age.value = random(ages);
  if (good && !good.value) good.value = random(goodThings);
  if (gift && !gift.value) gift.value = random(giftHints);
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('santa-form');
  const result = document.getElementById('santa-result');

  if (!form) return;

  if (form.dataset.autofill === '1') {
    autofillSample(form);
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (result) result.textContent = '生成中…（少し待ってね）';

    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    try {
      const res = await fetch('/bc-santa-message/api/generate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': getCsrfToken(),
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
        credentials: 'same-origin',
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok || !data.ok) {
        throw new Error(data.message || `エラーが発生しました (${res.status})`);
      }
      if (result) result.textContent = data.message;
    } catch (err) {
      if (result) result.textContent = '失敗しました: ' + (err?.message || err);
    }
  });
});
