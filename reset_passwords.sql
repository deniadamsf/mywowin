-- Script untuk mengubah password semua akun user / admin / superadmin di database lokal
-- Hash berikut adalah bcrypt untuk string: "password"

USE `u259615093_mywowin`;

UPDATE `users` 
SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    `status_aktif` = 'aktif'
WHERE `id` > 0;
