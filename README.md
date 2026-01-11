# Todo / Calendar アプリケーション  

<div align="center">
  <img alt="WSL2" src="https://img.shields.io/badge/wsl2-w?style=plastic&logo=linux&logoColor=000000&labelColor=%23FCC624&color=%23FCC624">
  <img alt="Ubuntu" src="https://img.shields.io/badge/ubuntu-u?style=plastic&logo=ubuntu&logoColor=%23ffffff&labelColor=%23E95420&color=%23E95420">
  <img alt="Alpine" src="https://img.shields.io/badge/alpine-l?style=plastic&logo=alpinelinux&logoColor=%23ffffff&labelColor=%230D597F&color=%230D597F">
  <img alt="Docker" src="https://img.shields.io/badge/Docker-d?style=plastic&logo=docker&logoColor=%23ffffff&labelColor=%232496ED&color=%232496ED">
  <img alt="NGINX" src="https://img.shields.io/badge/NGINX-n?style=plastic&logo=nginx&logoColor=%23ffffff">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-m?style=plastic&logo=mysql&logoColor=%23ffffff&labelColor=%234479A1&color=%234479A1">
  <img alt="PHP" src="https://img.shields.io/badge/php-p?style=plastic&logo=php&logoColor=%23ffffff&labelColor=%23777BB4&color=%23777BB4">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel12-l?style=plastic&logo=laravel&logoColor=%23ffffff&labelColor=%23FF2D20&color=%23FF2D20">
  <img alt="Tailwind" src="https://img.shields.io/badge/tailwind-%20?style=plastic&logo=tailwindcss&logoColor=ffffff&color=%2306B6D4">
  <img alt="Vite" src="https://img.shields.io/badge/vite-v?style=plastic&logo=vite&logoColor=%23ffffff&labelColor=%23646CFF&color=%23646CFF">
</div>

## プロジェクト概要
Laravel 12 を用いて開発した **Todo 管理 + 月次カレンダー表示を備えた Web アプリケーション**です。  
ユーザーごとにタスクを管理し、期限・時間・ステータスを持つ Todo を  
カレンダー上で視覚的に把握できる構成になっています。

実務を想定し、**責務分離・保守性・拡張性** を意識した設計を行っています。

## 主な機能
- ユーザー認証（登録 / ログイン / ログアウト）
- Todo 管理（CRUD）
  - タイトル / 詳細 / 期限
  - 優先度 / ステータス
  - 開始時間 / 終了時間
- タグ管理（Todo ↔ Tag 多対多）
- 月次カレンダー表示（日付ごとの Todo 一覧）
- 管理者機能
  - ユーザー一覧・編集・削除（`is_admin` フラグ）

## 使用技術
| カテゴリ | 使用技術 |
| :--- | :--- |
| **Backend** | Laravel 12, PHP_CodeSniffer, Debugbar |
| **Frontend** | Blade, Tailwind CSS, Alpine.js |
| **Infrastructure** | Docker Compose (App / Node / MySQL / Nginx) |
| **Testing** | Pest (Feature & Unit Testing) |
| **OS Environment** | WSL2 (Ubuntu / Alpine Linux) |
| **Database** | MySQL 8.x |

## セットアップ手順

### 1. インフラのビルドと起動
```
docker compose build
docker compose up -d
```

### 2. バックエンド・フロントエンドの初期化
```
docker compose exec app ash
composer install
php artisan migrate
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
npm install
npm run dev
``` 
### 3. テストの実行
本プロジェクトでは Pest を使用して自動テストを実装しています。
```bash
docker compose exec app php artisan test
```

## ディレクトリ構成（主要部分）
app/
├── Models/             # Eloquentモデル（データの定義とリレーション）
├── Http/Controllers/   # リクエストの橋渡し（Thin Controller）
├── Services/           # 【重要】複雑な計算やビジネスロジックの集約
└── Http/Middleware/    # 管理者権限（Admin）などのアクセス制限
resources/
├── views/              # Bladeテンプレート（UI）
└── js/css/             # TailwindCSS / Alpine.js のソースファイル

## 設計・実装の特徴

本プロジェクトでは、実務を想定し「責務分離」「保守性」「拡張性」を意識して設計・実装を行っています。

### サービス層の導入

- カレンダー描画や日付パースなど、複数箇所から再利用される処理は `app/Services/` に集約し、
  Controller の責務肥大化を防いでいます。

#### 主なサービス

- **CreateCalendarService**
  - 月表示用の 2 次元配列を生成
  - 月初・月末の余白計算
  - 日付ごとに Todo を割り当てる処理（`attachTodos()`）

- **DateKeywordParser**
  - ユーザー入力の日付文字列（例: `1/3`, `2026-01-03`）を正規化
  - `Y-m-d` 形式への変換を行うユーティリティ

---

### モデル設計とリレーション

- **User**
  - Todo と 1 対 多（`hasMany`）
  - 認証・認可の基点となるモデル

- **Todo**
  - User に属する（`belongsTo`）
  - Tag と多対多（`belongsToMany`）
  - `$fillable`, `$casts` を用いた安全なデータ操作
  - アクセサによる表示用フォーマットの提供

- **Tag**
  - Todo と多対多
  - `firstOrCreate()` による重複防止
  - `sync()` を用いた中間テーブル管理

---

### カレンダーと日付処理

- 月単位のカレンダー表示を独自に実装（Carbon を用いた日付計算を明示的に制御）
- 週配列化、月初・月末の余白処理を明示的に制御
- Todo を日付単位で紐付け、Blade 側では表示に専念できる構成

---

### 認証・認可と管理者機能

- Laravel の標準的な認証構成をベースに実装
- `is_admin` カラムによる管理者判定
- 管理者専用ルーティングとミドルウェアによるアクセス制御

---

### データ整合性と運用面の配慮

- マイグレーションで外部キー制約を定義し、参照整合性を担保
- ファイルアップロード（アバター）は `storage:link` を用いて管理
- ローカル環境での再現性を意識したセットアップ手順を用意

---

### テストコードによる品質担保 (Pest)

- **Feature Test**: 認証、Todo の CRUD、管理者権限、検索ロジックなど、全 33 項目のテストを実装し、機能の正常動作を担保しています。
- **認可のテスト**: `actingAs` を用いて、自分以外の Todo を削除・編集できないことや、一般ユーザーが管理画面にアクセスできないことを厳密に検証しています。

---

### UI/UX とエラーハンドリング

- **Empty State の考慮**: タスクが 0 件の際に、単に空のテーブルを出すのではなく「現在タスクはありません」というメッセージと登録を促す案内を表示し、ユーザー迷わせない設計を行っています。
- **操作性の集約**: 一覧画面での「詳細・編集・削除」ボタンを 1 カラムに集約。Flexbox を用いて中央配置し、視認性と誤操作防止を両立させています。
## 今後の改善予定
- 問い合わせフォーム（メール送信）
- 管理者向けシステム通知機能
- Todo 期限の通知（リマインダー）機能
- カテゴリ別の進捗グラフ表示
