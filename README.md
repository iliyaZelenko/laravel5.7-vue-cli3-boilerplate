# Boilerplate / Starter kit. Laravel 5.7, Vue CLI 3 SPA with Auth.


<p align="center">
  <img src="https://i.imgur.com/dSyP2vt.png" width="240" height="210">
  <img src="https://i.imgur.com/dSyP2vt.png" width="240" height="210">
  <img src="https://i.imgur.com/VWp0RM4.png" width="240" height="210">
  <img src="https://i.imgur.com/npqyrCQ.png" width="240" height="210">
  <img src="https://i.imgur.com/pwoiWwi.png" width="240" height="210">
  <img src="https://i.imgur.com/Vu98vNv.png" width="240" height="210">
</p>


## Features
- Laravel 5.7 Verification via API!
- Forgot password and Reset password
- Profile with user info
- Password change
- Auth through [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) with refresh token functional
- Auth route middleware(guest, auth)
- Bootstrap 4
- [vue-notifications](https://github.com/se-panfilov/vue-notifications#readme) (with [iziToast](https://github.com/dolce/iziToast)) There is no attachment to a particular library!
- [Vee-validate](https://github.com/baianat/vee-validate) validation
- Awesome vue-plugin-axios


## Installation

1. `git clone https://github.com/iliyaZelenko/laravel5.7-vue-cli3-boilerplate.git`
2. `composer install`
3. Copy `.env.example` to `.env` and set your database connection details and `FRONTEND_URL`, `APP_URL`
4. Generate the Laravel app key: `php artisan key:generate`
5. To make the JWT authorization work: `php artisan jwt:secret` (it generates `JWT_SECRET` in `.env`)
6. If you want mail verification to work, then configure `MAIL_USERNAME` and `MAIL_PASSWORD` in `.env`
7. `php artisan migrate:fresh --seed` make tables and users
8. `cd frontend`
9. Edit `.env` to set your `VUE_APP_BACKEND`(backend url)
10. `yarn` or `npm install`

Frontend is in the folder **frontend**, the following commands for this folder:

### Compiles and hot-reloads for development
```
yarn serve // OR npm run install
```
For laravel server you can run `php artisan serve`(in root directory)

### Compiles and minifies for production
```
yarn build // OR npm run build
```

You can open your finished build via laravel [SpaController](https://github.com/iliyaZelenko/laravel5.7-vue-cli3-boilerplate/blob/master/app/Http/Controllers/SpaController.php)!
The index file in `app/resorces/views/index.blade.php` content is generated via vue!
Your css, js, img, etc... will be added to the `app/public` folder!

### Lints and fixes files
```
yarn lint // OR npm run lint
```

### Run your unit tests
```
yarn test:unit // OR npm run test:unit
```

### Run your end-to-end tests
```
yarn test:e2e // OR npm run test:e2e
```

Also i have vuetify and nuxt + vuetify version! 
Let me know if you are interested in this project.

### TODO (support me with a star)
- i18n
- avatar
- select timezone and display date/time for user timezone
- socialite
- all users have public profile and own settings page
- unit and end-to-end tests
