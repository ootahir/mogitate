# 環境構築

## Dockerビルド

- git clone git@github.com:yukit4mu/test_contact-form.git
- docker-compose up -d --build

## Laravel環境構築

- docker-compose exec php bash
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed

## 開発環境

- 商品一覧画面：http://localhost/products
- 商品詳細：http://localhost/products/detail/{:productId}
- 商品更新：http://localhost/pdoducts/{:productId}/update
- 商品登録：http://localhost/pdoducts/regsiter
- 検索：http://localhost/pdoducts/search
- 削除：http://localhost/pdoducts/{:productId}/delete

## 使用技術（実行環境）

- PHP 8.2.11
- Laravel 8.83.8
- jquery 3.7.1.min.js
- MySQL 8.0.26
- nginx 1.21.1

## ER図
<img width="1406" height="1153" alt="image" src="https://github.com/user-attachments/assets/1e5a9888-2c49-41a9-b5db-1b1134e5f126" />

