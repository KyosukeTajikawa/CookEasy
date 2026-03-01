# Nginx default.conf の解説

## このファイルの役割

`docker/nginx/default.conf` は **Docker コンテナ内の Nginx に読み込ませる設定ファイル**。
`compose.yaml` の以下の記述によってコンテナ起動時に自動で読み込まれる。

```yaml
volumes:
  - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
```

> ローカル直接起動ではなく、Docker 環境専用の設定。

---

## リクエストの全体フロー

```
ブラウザ
  ↓  localhost:8080 にアクセス
Nginx（web コンテナ / ポート 80）
  ↓  .php ファイルへのリクエストなら app:9000 に転送
PHP-FPM（app コンテナ / ポート 9000）
  ↓  必要なら
MySQL（db コンテナ）
  ↓  結果を返す
PHP-FPM → Nginx → ブラウザに表示
```

---

## ポートマッピング（8080:80）

書き方は `ホスト側:コンテナ側`。

| ポート | 場所 | 意味 |
|---|---|---|
| `8080` | Mac（ブラウザ側） | ブラウザでアクセスする番号 |
| `80` | コンテナ内 | Nginx が待ち受けている番号 |

Mac の 80 番ポートは他アプリ（Herd など）が使っている可能性があるため、外側だけ 8080 にずらして衝突を避けている。

---

## 全コード

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 1行ずつの解説

### 基本設定

| 行 | 内容 | 説明 |
|---|---|---|
| `listen 80;` | 80番ポートで待ち受け | HTTP の標準ポート |
| `server_name localhost;` | 担当ドメインの指定 | 本番なら `cookeasy.com` など |
| `root /var/www/html/public;` | 公開ルートフォルダ | `public/` 以外は直接アクセス不可 |
| `index index.php index.html;` | デフォルトファイル | URL がフォルダで終わった場合に探す順序 |

---

### リクエストの振り分け

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

`/` で始まる全 URL に適用。左から順にファイル・フォルダの存在を確認し、なければ Laravel の `index.php` に委ねる。

| 試す順 | 意味 |
|---|---|
| `$uri` | ファイルが実際に存在するか（画像・CSS など） |
| `$uri/` | ディレクトリが存在するか |
| `/index.php?$query_string` | どちらもなければ Laravel のルーターに渡す |

**例：** `/recipes/1` にアクセス
→ ファイルもフォルダも存在しない
→ `index.php` に渡して Laravel がルーティング処理する

---

### PHP の処理

```nginx
location ~ \.php$ {
    fastcgi_pass app:9000;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

| 行 | 説明 |
|---|---|
| `location ~ \.php$` | `.php` で終わる URL にだけ適用（`~` は正規表現） |
| `fastcgi_pass app:9000;` | PHP 処理を `app` コンテナの 9000 番に転送（9000 は PHP-FPM のデフォルト） |
| `fastcgi_index index.php;` | PHP リクエストでファイル名省略時のデフォルト |
| `fastcgi_param SCRIPT_FILENAME ...` | PHP-FPM に渡す実行ファイルの絶対パスを指定 |
| `include fastcgi_params;` | Nginx 標準の FastCGI 変数（メソッド・サーバー情報など）をまとめて読み込み |

`SCRIPT_FILENAME` の値は
`$realpath_root`（= `/var/www/html/public`）+ `$fastcgi_script_name`（= `/index.php`）
→ `/var/www/html/public/index.php` になる。

---

### セキュリティ設定

```nginx
location ~ /\.(?!well-known).* {
    deny all;
}
```

`.` で始まる隠しファイルへのアクセスをすべて拒否する。

| 種別 | 例 | 結果 |
|---|---|---|
| 拒否 | `.env`（DB パスワードなど） | アクセス不可 |
| 拒否 | `.git/`（ソースコード履歴） | アクセス不可 |
| 例外で許可 | `.well-known/`（SSL 証明書認証） | アクセス可 |

`(?!well-known)` は正規表現の否定先読みで、`.well-known` だけを除外している。
