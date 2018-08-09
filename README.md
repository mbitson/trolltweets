# [TrollTweets.com](http://trolltweets.com/)
Vue 2 app for navigating and auditing the tweets that were released by [FiveThirtyEight](https://fivethirtyeight.com/features/why-were-sharing-3-million-russian-troll-tweets/) on [GitHub](https://github.com/fivethirtyeight/russian-troll-tweets/). Uses a backend API built with Laravel 5.6.

![TrollTweets.com App Screenshot](./public/screenshot.png)

## Table of Contents

* [Installation](#installation)
* [Changelog](#changelog)
* [Planned Enhancements](#planned-enhancements)
* [Known Issues](#known-issues)
* [Contributions](#contributions)
* [Credits](#credits)
    
# Installation
#### 1) Checkout Repo
```bash
git clone git@github.com:mbitson/trolltweets.git troll-tweets
```
#### 2) Pull Submodules
```bash
git submodule init
git submodule update
# Note that this includes this submodule: https://github.com/fivethirtyeight/russian-troll-tweets/
```
#### 3) Install composer dependencies
```bash
composer install
```
#### 4) Configure Laravel
```bash
cp .env.example .env
# Configure .env with database settings (may require you create a DB)
```
#### 5) Generate Laravel Key
```bash
php artisan key:generate
```
#### 6) Migrate Database
```bash
php artisan migrate
```
#### 7) Ensure MySQL Timezone is set.
The timezone must not have a daylight savings time or some inserts will fail.
```mysql
SET time_zone = "+00:00";
SET @@global.time_zone = '+00:00';
```
#### 8) Seed Tweets
This process may take awhile. It will insert new database records for all ~3 million records of the FiveThirtyEight data. You may edit the $recordsPerInsert in TweetTableSeeder.
```bash
php artisan db:seed
# Will pull tweet data from database/seeds/russian-troll-tweets (a submodule of this repo)
```
#### 9) Generate Assets
```bash
npm install
npm run dev #or `npm run watch` to watch files for changes
```
#### 10) Serve The Site
```bash
php artisan serve
```
That is it! You should now be able to access the site at [http://localhost:8000/](http://localhost:8000/).

## Changelog
- 8/7/2018 - Initial launch of the site at [trolltweets.com](http://trolltweets.com/)

## Planned Enhancements
- Add a filter bar showing current filters without opening the bottom sheet.
- Add a table-style "Data Browser" using the same global filters.
- Allow the user to obtain a permalink that applies all current filters.

## Known Issues
- The total on the by category text listing section appears to be incorrect.

## Contributions
Contributions to this repository are welcome. Simply submit a PR to the `develop` branch and wait for approval. If you have any questions or concerns about the codebase please open an issue.

## Credits
- [Mikel Bitson](http://mbitson.com/)