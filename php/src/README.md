# Todo / Calendar アプリケーション（転職 / ポートフォリオ向け）

このリポジトリは、Laravel ベースで実装した Todo（タスク管理）とカレンダー表示を備えた Web アプリケーションです。
転職活動やポートフォリオで「何を」「どのように」実装したかを効率的に説明できるよう、設計上の判断、主要な実装ポイント、技術スタック、デモ手順、面接でのアピール例をまとめています。

## 概要 / このリポジトリで示せるスキル

- ユーザー認証（登録・ログイン・ログアウト）を自前で実装した経験
- ドメインモデル設計（Todo / Tag / User のリレーション設計）
- ビジネスロジックと表示ロジックの分離（サービス層の導入）
- カレンダー表示ロジック（週配列・余白処理・日付ごとのタスク割当て）の実装
- ファイルアップロード（アバター）、ストレージ公開、マイグレーションによるスキーマ設計

これらは、バックエンド実装力・設計判断・運用考慮を面接で示すのに適した事例です。

## 主な機能（面接でデモできる単位）

- ユーザー認証（自作）
- プロフィール編集（アバター対応、`avatar_path` 保存）
- Todo の CRUD（タグ同期、ステータス・優先度の管理）
- カレンダー表示（月単位のビュー、日ごとに Todo を割当てる処理）
- 管理者画面（`is_admin` に基づくユーザー管理）

デモの提示例:
- ログイン → Todo 作成 → タグ付与 → カレンダーで該当日に表示されることを見せる
- 管理者でユーザー一覧を表示・編集する流れを見せる

## 技術スタック（面接で話すべきポイント）

- PHP 8.2 / Laravel 12
	- なぜ Laravel を選んだか（生産性、Eloquent、ミドルウェアなど）を簡潔に説明できるようにしておくと良いです。
- Eloquent ORM（モデル設計とリレーション）
- サービス層（`app/Services`）によるビジネスロジックの分離
- Vite / TailwindCSS / Alpine.js（軽量フロントエンドの構成）

主要ファイル（面接で参照する候補）:
- `app/Models/Todo.php`, `app/Models/Tag.php`, `app/Models/User.php`（ドメインモデル）
- `app/Services/CreateCalendarService.php`（カレンダーロジック）
- `app/Services/DateKeywordParser.php`（日付パースユーティリティ）
- `routes/web.php`（画面遷移の流れ）

拡張：使用している主なライブラリ・ツール（面接で具体的に触れると良い項目）

- Blade（Laravel のテンプレートエンジン） — サーバーサイドでのビュー生成
- Eloquent（ORM） — リレーション設計やアクセサ／ミューテタの利用
- Carbon（日付操作） — カレンダー処理や日付フォーマットに使用
- PHP の Enum（`app/Enums`） — ステータスや優先度を列挙型で扱う設計
- Axios — フロントエンドからの非同期通信（API 呼び出し）に利用
- Vite + laravel-vite-plugin — フロントエンドのビルドとアセット管理
- TailwindCSS / Alpine.js — 軽量で保守しやすい UI 実装
- Composer / NPM — パッケージ管理とスクリプト実行
- PHPUnit / Mockery — 単体テスト・モックの実装に使用
- Laravel Debugbar（開発時のデバッグ補助）
- マイグレーション / シーダー / ファクトリ — スキーマ管理とテストデータ準備
- `php artisan` スクリプト（`storage:link`, `migrate`, `db:seed` 等）による運用手順

加えて、`composer.json` の開発ツール群（`pint`, `pail`, `sail` など）や、ロギング・キューなどの Laravel 組み込み機能をプロジェクトで利用できます。面接では「どのライブラリを使い、なぜ使ったか」を短く説明できると良いです。

## セットアップ（短く、採用面接で実演するための手順）

リポジトリをクローンして、以下を実行すればローカルで動かせます。デモ用に SQLite を使う方法を示します。

```sh
git clone <your-repo-url>
cd <repo-directory>
composer install
npm install
cp .env.example .env
# SQLite を使う場合
mkdir -p database
touch database/database.sqlite
sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run dev
php artisan serve
```

デモで実行するコマンド（短く示す）:

```sh
# テスト
php artisan test

# 開発サーバー
php artisan serve
```

※ 本番運用向けの設定（env のシークレット管理、HTTPS、ファイルストレージの S3 など）は別途説明できると印象が良いです。

## ディレクトリ構成（採用面接で参照しやすいポイント）

- `app/Models/` - ドメインモデル（重要: Eloquent リレーション、アクセサ／ミューテタの使いどころを説明）
- `app/Http/Controllers/` - ルーティングと画面遷移の責務（コントローラーは薄く保ち、サービスに委譲している点を示す）
- `app/Services/` - 再利用可能なビジネスロジック（カレンダー描画、日付パース）
- `database/migrations/` - スキーマ変更の履歴（外部キー、データ型の選定、デフォルト値の理由）
- `resources/views/` - Blade テンプレート（UI/UX の簡単な説明、Tailwind の導入理由）
- `routes/web.php` - ルーティングとミドルウェア（`auth`, `admin` の使い分けを説明）

面接では `app/Services/CreateCalendarService.php` と `app/Models/Todo.php` を開いて、設計判断を具体的に説明できることが有効です。


## 転職（面接）向けチェックリスト — デモ & 質問に備える

以下は面接で実演・説明すると印象が良いポイントです。準備しておくと実務的な深掘りに耐えられます。

- アーキテクチャ説明: サービス層を採用した理由、コントローラーを薄くした利点
- データモデル: `Todo` と `Tag` の多対多設計、中間テーブルの扱い（`sync`, `firstOrCreate`）
- カレンダー実装: 月表示ロジック（余白計算、週配列化）、`attachTodos()` での割当て処理
- 認証: 自作した理由、パスワードハッシュ・CSRF 対策・バリデーションの実装箇所
- テスト: どのレイヤーにユニット / 統合テストを書いたか（`php artisan test` での実行例）
- 運用上の配慮: マイグレーションの可逆性、ストレージの扱い、ログの確認方法
- セキュリティ: パスワードハッシュ、ファイルアップロードのサニタイズ、認可（admin ミドルウェア）

実演するための最小手順（サンプル）:

1. サイトに登録してログイン
2. Todo を 1 件作成し、タグを追加
3. カレンダービューに移動して、作成した日付に Todo が表示されることを確認

面接で「実装したファイル」を示すなら、`app/Services/CreateCalendarService.php`（ロジック）→ `app/Http/Controllers/Calendar/CalendarController.php`（呼び出し）→ Blade（表示） の順に見せると説明が伝わりやすいです。


## 参考（リポジトリから読み取れる実装情報）

- PHP バージョン要件：^8.2（`composer.json`）
- Laravel バージョン：^12.0（`composer.json`）
- フロントエンド：Vite, TailwindCSS, Alpine.js（`package.json`）
- 主なモデル：`app/Models/Todo.php`, `app/Models/Tag.php`, `app/Models/User.php`
- カレンダー処理：`app/Services/CreateCalendarService.php`
- 日付キーワード変換：`app/Services/DateKeywordParser.php`

---

この README は転職活動での説明用に焦点を当てています。さらに「スクリーンショット」「ER図」「コードスニペット（主要関数の入出力例）」「CI 設定（GitHub Actions）」などを追加すると、より説得力のあるポートフォリオになります。追記したい項目を教えてください。

## 設計・実装の特徴

このプロジェクトで観察できる設計上・実装上の特徴を初心者向けにまとめます。

- サービス層の導入
	- カレンダー描画や日付パースなど、複数のコントローラーで使うロジックは `app/Services/` に切り出されています。
	- 例：`CreateCalendarService::drawDayArray()` は月表示用の 2 次元配列を作成し、`attachTodos()` でタスクを紐付けます。ロジックをサービスにまとめることでコントローラーが薄く保たれ、再利用しやすくなっています。

- モデル設計とリレーション
	- `Todo` と `Tag` は多対多（belongsToMany）でつながっており、中間テーブル `tag_todo` を経由してタグの同期（`sync`）や追加（`firstOrCreate`）を行います。
	- `User` は `hasMany` で `Todo` を持ちます。認可・表示はログインユーザー（`auth()`）を基準に行われます。
	- `Todo` では `$fillable` や `$casts` を使って安全に値を受け取り、日付の整形メソッド（`getFormattedDeadlineAttribute`）などアクセサを定義しています。

- カレンダーと日付処理
	- `CreateCalendarService` は「月の先頭余白・末尾余白の計算」「週ごとの配列化」「日付へタスクを割り当てる」など、カレンダー固有のロジックを扱います。
	- `DateKeywordParser` はユーザーが入力する「1/3」や「2026-01-03」などの文字列を正規化して `Y-m-d` に変換するユーティリティです。入力例を想定した堅牢なパース処理が含まれます。

- タグの扱い
	- タグは空白を許容したり、未使用タグを削除するロジック（`Todo::deleteTodolist()` 内で detach 後に参照がないタグを削除）を持っています。
	- タグの生成は `Tag::firstOrCreate()` を使って重複を避け、`sync()` で中間テーブルを更新します。

- 認可と管理者機能
	- ルーティングでは `auth` ミドルウェアによる保護に加え、管理者用に `admin` ミドルウェアとプレフィックス `admin/` を設定しています（`is_admin` カラムをベースにした権限制御が想定されます）。


- 認証・プロフィール（自作）
	- このプロジェクトでは認証（登録・ログイン）とプロフィール編集機能をフルスクラッチで実装しています。Laravel の Breeze や Jetstream のようなスターターパッケージを導入していないため、アプリ固有の要件に合わせてコントローラーやフォーム、バリデーションを定義しています。
	- 主な実装箇所:
		- `app/Http/Controllers/Auth/RegisterController.php`（ユーザー登録処理）
		- `app/Http/Controllers/Auth/LoginController.php`（ログイン / ログアウト処理）
		- `app/Http/Controllers/Profile/ProfileController.php`（プロフィール編集・更新）
	- ユーザー情報には `avatar_path`（アバター画像パス）や `is_admin`（管理者フラグ）などのカラムがあり、これらはマイグレーションで追加されています（例: `database/migrations/*add_is_admin_to_users_table.php`, `*add_avatar_path_to_users_table.php`）。
	- 注意点（初心者向け）:
		- 自作認証ではセキュリティ（パスワードハッシュ、CSRF、バリデーション）に注意してください。既存のスターターパッケージを使う場合と違い、これらを自分で正しく実装・確認する必要があります。
		- アバター画像を扱う場合は `php artisan storage:link` を実行して `storage` を公開し、アップロード先のパーミッションやファイルサイズ制限・拡張子チェックを実装してください。

- マイグレーションとデータ整合性
	- マイグレーションで外部キー制約（`foreignId('user_id')->constrained()`）を使い、参照整合性を保っています。

- 小さな工夫（読みやすさ・保守性）
	- サービス／モデル／コントローラーの責務分離によりテストや修正がしやすくなっています。
	- `composer.json` に `scripts`（`setup`, `dev`, `test`）が用意されており、ローカルセットアップや開発起動が簡単に再現できます。

これらの特徴は学習用として良い実践例になり得ます。必要であれば各ポイントごとにコード例や図を README に追加して説明を深めます。

---

この README は最低限の説明を目的としています。より詳しい使い方や開発ルール（コードスタイル、テスト方針、デプロイ手順など）を追加したい場合は、欲しいセクションを教えてください。README の改善も手伝います。

