#![cfg_attr(windows, feature(abi_vectorcall))]
use ext_php_rs::prelude::*;
use magic_crypt::{new_magic_crypt, MagicCryptTrait};

#[php_function]
pub fn nativephp_crypt(content: &str) -> String {
    let ck = env!("NATIVEPHP_CRYPT_KEY", "$NATIVEPHP_CRYPT_KEY is not set");

    let mc = new_magic_crypt!(ck, 256);

    let base64 = mc.encrypt_str_to_base64(content);

    return base64;
}

#[php_function]
pub fn nativephp_decrypt(content: &str) -> String {
    let ck = env!("NATIVEPHP_CRYPT_KEY", "$NATIVEPHP_CRYPT_KEY is not set");

    let mc = new_magic_crypt!(ck, 256);

    return mc.decrypt_base64_to_string(&content).unwrap();
}

#[php_module]
pub fn get_module(module: ModuleBuilder) -> ModuleBuilder {
    module
}
