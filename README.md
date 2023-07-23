# NativePHP Source Code Encryption Test

This project is created to tinker a method to encrypt the source code of a PHP project in NativePHP packages.

It has 2 files, `encrypt.php` and `libnativephp_crypt.so`:

- The `libnativephp_crypt.so` is a PHP extension that basically has two methods: `nativephp_crypt` and `nativephp_decrypt` which are used to encrypt and decrypt the source code respectively. It is written in Rust with `ext-php-rs`. It gets the encryption key from the environment variable `NATIVEPHP_CRYPT_KEY` in build time.

- The `encrypt.php` is an example script that encrypts all `php` and `blade.php` files in the source code with `nativephp_crypt` and replaces them with a file that contains php code which decodes the encrypted code with `nativephp_decrypt()` method.

## How to test

1. Clone this repository

```bash
git clone alperenersoy/nativephp-encryption-test
```

2. Create a new Laravel project

```bash
composer create-project laravel/laravel    
```

3. Copy `encrypt.php` to the root of the Laravel project

```bash
cp encrypt.php ./laravel/encrypt.php
```

4. Run `encrypt.php` with the extension

```bash
php -d extension=./libnativephp_crypt.so ./laravel/encrypt.php
```

5. Serve the Laravel project with the extension

```bash
php -d extension=./libnativephp_crypt.so -S 127.0.0.1:8080 ./laravel/public/index.php
```

## Things to improve

- The extension built with `ext-php-rs` is too big. I guess the native `C` version of the extension will be smaller. It is a big problem for NativePHP packages since they are supposed to be small.
- The extension should be built in the build time of the package to provide different keys for different applications.
- You can easily add `dd(nativephp_decrypt($path));` to the replacement files to see the decrypted code. The extension should be able to detect if replacement files has been modified and should not decrypt the code if they are modified.