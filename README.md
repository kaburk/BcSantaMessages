# BcSantaMessage

生成AI（Gemini / Ollama）を使ってサンタからのメッセージを自動生成する baserCMS 用プラグインです。

詳しい記事はこちら https://blog.kaburk.com/blog/archives/188

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![baserCMS](https://img.shields.io/badge/baserCMS-5.x-green.svg)

## 概要

BcSantaMessage は、baserCMS でサンタクロースからのメッセージを生成AIで自動生成できるプラグインです。
フロントエンドのフォームから名前や年齢、メッセージの内容を入力すると、Gemini API または Ollama を使用して、サンタからの温かいメッセージを自動生成します。

## 主な機能

- 🎅 **サンタからのメッセージ自動生成**: 生成AIでパーソナライズされたメッセージを作成
- 🤖 **複数のAIプロバイダー対応**: とりあえず Gemini API と Ollama の両方をサポート
- ⚙️ **管理画面での設定**: API設定、レート制限、機能の有効/無効を管理画面から設定可能
- 📝 **生成履歴の保存**: 生成されたメッセージはデータベースに保存され、管理画面で確認可能
- 🛡️ **レート制限**: 連続リクエストを制限してサーバー負荷を軽減
- 🎨 **カスタマイズ可能なフォーム**: テンプレートで自由にカスタマイズ可能

## 動作環境

- baserCMS 5.x
- PHP 8.0 以上
- CakePHP 5.x

## インストール

### 1. プラグインの配置

baserCMS のプラグインディレクトリに配置します：

```bash
cd /path/to/basercms/plugins/
git clone https://github.com/your-username/BcSantaMessage.git
```

### 2. プラグインの有効化

baserCMS の管理画面から：

1. 「システム管理」→「プラグイン管理」
2. BcSantaMessage を「有効化」

## 設定

### AI プロバイダーの設定

管理画面「BcSantaMessage 設定」（`/bc-santa-message/admin/santa-message`）から設定します。

#### Gemini API を使用する場合

1. [Google AI Studio](https://makersuite.google.com/app/apikey) で API キーを取得
2. プロバイダーで「Gemini」を選択
3. API キーを入力
4. モデルを選択（例: `gemini-1.5-flash`）

#### Ollama を使用する場合

1. Ollama をローカル環境にインストール
2. プロバイダーで「Ollama」を選択
3. エンドポイントURL（デフォルト: `http://localhost:11434`）を設定
4. 使用するモデル名を入力（例: `llama3`）

### その他の設定項目

- **機能の有効/無効**: プラグイン全体の動作を制御
- **レート制限**: 連続リクエストを制限する秒数（0 で無効化）

## 使い方

### フロントエンドでの利用

プラグインを有効化すると、以下のURLでサンタメッセージ生成フォームが表示されます：

```
https://your-domain.com/santa-messages
```

フォームに以下の情報を入力：

- 名前（必須）
- 年齢（任意）
- サンタへのメッセージ（任意）

「メッセージを生成」ボタンをクリックすると、AIがサンタからのメッセージを生成します。

## 管理機能

### 生成履歴の確認

管理画面「BcSantaMessage 設定」から、生成されたメッセージの履歴を確認できます：

- 生成日時
- 使用したAIプロバイダー
- モデル名
- 入力内容（名前、年齢、メッセージ）
- 生成されたメッセージ

## 開発

### ディレクトリ構造

```
BcSantaMessage/
├── config/
│   ├── Migrations/           # データベースマイグレーション
│   ├── bootstrap.php
│   └── routes.php
├── src/
│   ├── Controller/
│   │   ├── Admin/            # 管理画面コントローラー
│   │   └── SantaMessagesController.php  # フロント用コントローラー
│   ├── Form/
│   │   └── SantaMessageForm.php  # フォーム検証
│   ├── Model/
│   │   ├── Entity/
│   │   └── Table/
│   │       └── SantaMessagesTable.php  # メッセージテーブル
│   ├── Service/
│   │   ├── Ai/
│   │   │   ├── AiClientFactory.php     # AIクライアントファクトリー
│   │   │   ├── AiClientInterface.php   # AIクライアントインターフェース
│   │   │   ├── GeminiClient.php        # Gemini API クライアント
│   │   │   └── OllamaClient.php        # Ollama クライアント
│   │   └── SettingsService.php         # 設定サービス
│   └── BcSantaMessagePlugin.php
├── templates/
│   ├── Admin/                # 管理画面テンプレート
│   ├── SantaMessages/        # フロント画面テンプレート
│   └── element/              # 共通エレメント
├── webroot/
│   ├── css/
│   └── js/
│       └── santa_message.js  # フロントエンドJS
├── config.php                # プラグイン設定
├── LICENSE
├── README.md
└── VERSION.txt
```

## セキュリティ

- API キーは必ず環境変数や設定ファイルで安全に管理してください
- レート制限を有効にして、DoS攻撃を防ぎましょう
- ユーザー入力は必ずバリデーションを通してから処理されます

## トラブルシューティング

### メッセージが生成されない

1. 管理画面でプラグインが有効化されているか確認
2. AI プロバイダーの設定（API キー、エンドポイント）を確認
3. ログファイル（`logs/debug.log`、`logs/error.log`）を確認

### Ollama が接続できない

- Ollama が起動しているか確認：`ollama list`
- Docker 環境の場合、`host.docker.internal` を使用してホストマシンに接続
- ファイアウォール設定を確認

## ライセンス

MIT License

Copyright (c) 2025 kaburk

詳細は [LICENSE](LICENSE) ファイルを参照してください。

## 作者

kaburk

## リンク

- [baserCMS 公式サイト](https://basercms.net/)
- [baserCMS GitHub](https://github.com/baserproject/basercms)
- [Google Gemini API](https://ai.google.dev/)
- [Ollama](https://ollama.ai/)

## 貢献

プルリクエストを歓迎します！バグ報告や機能リクエストは Issue でお知らせください。

---

🎄 Merry Christmas! 🎅
