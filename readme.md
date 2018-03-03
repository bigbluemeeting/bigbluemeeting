## Laravel 5.6 using Spatie's - Laravel Permission and Modular Admin.

This is a Laravel 5.6 adminpanel starter project with roles-permissions based on [Spatie's Laravel-permission](https://github.com/spatie/laravel-permission) and [Modular Admin](https://github.com/modularcode/modular-admin-html).

![Laravel 5.6 using Spatie's - Laravel Permission and Modular Admin](/screenshot/Laravel5.6_role_permission_modular_admin-min.PNG?raw=true)

## Usage

This is not a package - it's a full Laravel project that you should use as a starter boilerplate, and then add your own custom functionalities.

- Clone the repository with `git clone`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed` (it has some seeded data - see below)
- That's it: launch the main URL and login with default credentials `admin@admin.com` - `password`

This boilerplate has one role (`administrator`), two permission (`users_manage`,`master_manage`) and one administrator user.

With that user you can create more roles/permissions/users, and then use them in your code, by using functionality like `Gate` or `@can`, as in default Laravel, or with help of Spatie's package methods.

## License

No License, use it however you want.