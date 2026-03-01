# compose.yaml の解説

## 全体構成

このプロジェクトは3つのコンテナで構成されている。

| コンテナ名 | 役割 | イメージ |
|---|---|---|
| `app` | PHP-FPM（Laravel の処理） | Dockerfile でビルド |
| `web` | Nginx（リクエストの受け口） | nginx:alpine |
| `db` | MySQL（データベース） | mysql:8.0 |

---

## 起動順序

```
db 起動
  ↓ healthcheck PASS まで待つ（MySQL が本当に使える状態）
app 起動（PHP-FPM）
  ↓ app が起動するまで待つ
web 起動（Nginx）
  ↓
ブラウザからアクセス可能
```

---

## リクエストの全体フロー

```
ブラウザ
  ↓  localhost:8080 にアクセス
Nginx（web コンテナ / ポート 80）
  ↓  .php リクエストなら app:9000 に転送
PHP-FPM（app コンテナ / ポート 9000）
  ↓  必要なら
MySQL（db コンテナ）
  ↓  結果を返す
PHP-FPM → Nginx → ブラウザに表示
```

---

## networks（各コンテナの networks セクション）

```yaml
networks:
  - cookeasy
```

`app`・`web`・`db` の3つ全てに書いてある。
「このコンテナを `cookeasy` ネットワークに参加させろ」という指示。

**同じネットワークに属するコンテナはコンテナ名でお互いを呼び出せる。**

例：`default.conf` に `fastcgi_pass app:9000;` と書けるのはこのため。

### 例え：社内内線電話

| 状況 | 意味 |
|---|---|
| 同じネットワーク（cookeasy）に属している | 同じ会社のフロアにいる |
| コンテナ名で通信できる | 名前で内線をかけられる |
| 別ネットワークのコンテナ | 別の会社 → 内線では繋がらない |

---

## networks: cookeasy: driver: bridge

```yaml
networks:
  cookeasy:
    driver: bridge
```

「`cookeasy` という名前のネットワークを作成し、種類は `bridge` にする」という定義。

`bridge` は Docker の標準ネットワーク方式で、**Mac（ホスト）とは隔離されたコンテナ専用の仮想ネットワーク**を作る。

### 例え：会社の内線と外線

| 種類 | 意味 |
|---|---|
| `bridge` ネットワーク | 会社の内線（コンテナ同士が話す専用回線） |
| Mac（ホスト） | 外の世界 |
| `ports: 8080:80` | 外線番号（外から内線につながる入口） |

---

## db の healthcheck

```yaml
healthcheck:
  test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-proot_secret"]
  interval: 10s
  timeout: 5s
  retries: 5
```

「MySQL が本当に使える状態かどうかを定期的に確認する」仕組み。

| 行 | 内容 | 説明 |
|---|---|---|
| `test: [...]` | 確認コマンド | `mysqladmin ping` で MySQL に返事があるか叩く |
| `interval: 10s` | 確認の間隔 | 10秒ごとに確認する |
| `timeout: 5s` | タイムアウト | 5秒以内に返事がなければ失敗とみなす |
| `retries: 5` | リトライ回数 | 5回連続失敗したら「起動失敗」と判断する |

### 例え：お店の開店確認

10秒ごとにドアをノックして MySQL が応答するか確認する。
5秒待っても応答なければ失敗。5回連続失敗したら閉店中と判断する。

---

## condition: service_healthy

```yaml
depends_on:
  db:
    condition: service_healthy
```

「`db` コンテナが healthcheck に合格するまで `app` を起動しない」という指示。

### なぜ必要か

Docker はコンテナを素早く起動するが、**MySQL の中身が使える状態になるまでには数秒かかる**。

**ない場合：**
```
db コンテナ起動（MySQL の準備中）
  ↓ すぐ
app コンテナ起動 → MySQL に接続 → エラー（まだ準備できていない）
```

**ある場合：**
```
db コンテナ起動
  ↓ healthcheck PASS まで待機（最大 10s × 5回 = 50秒）
app コンテナ起動 → MySQL に接続 → 成功
```

### depends_on の2種類

| 書き方 | 意味 |
|---|---|
| `depends_on: - app`（web の設定） | app コンテナが**起動したら**すぐ続く |
| `depends_on: db: condition: service_healthy` | db が**本当に使える状態になるまで**待つ |

### 例え：飲食店のオープン準備

`depends_on: - app` は「前の人がドアを開けたら入る」シンプルな順番待ち。
`condition: service_healthy` は「仕込みが完全に終わった（healthcheck PASS）を確認してから客を入れる」より厳密な待機。
