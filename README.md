## About

This is a ChatGPT integration with Laravel, Vue.js and TailwindCss.

## Local Development

To build locally, use the following commands:

```bash
git clone git@github.com:shaunthornburgh/laravel-chatgpt-demo.git
cd laravel-chatgpt-demo
composer install
./vendor/bin/sail up -d
./vendor/bin/sail migrate --seed
./vendor/bin/sail npm run dev
```
