# Role-Based Access Control (RBAC) Assessment

![RBAC Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat-square) ![RBAC](https://img.shields.io/badge/RBAC-Enabled-green?style=flat-square) ![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

📌 **Tes Assessment Role-Based Access Control (RBAC)** adalah aplikasi berbasis **Laravel** yang menerapkan sistem hak akses pengguna berdasarkan peran tertentu (**Admin, Manager, dan User**).  
Sistem ini memungkinkan pengelolaan pengguna dengan akses yang berbeda, serta menyediakan fitur CRUD untuk manajemen role, user, dan laporan.

## ✨ **Fitur Utama**
✅ **Manajemen Role & Permissions**
   - Admin bisa menambahkan, menghapus, dan mengubah role pengguna.
   - Setiap pengguna memiliki peran tertentu (Admin, Manager, User).
   - Hak akses ditentukan menggunakan **Spatie Laravel Permission**.

✅ **Manajemen Pengguna**
   - **Admin** dapat melihat semua user, menambah, mengedit, dan menghapus pengguna.
   - **Manager** hanya bisa melihat user yang memiliki `manager_id` sesuai dengan ID mereka.
   - **User** hanya bisa melihat dan mengelola profil mereka sendiri.

✅ **Manajemen Laporan**
   - **User** dapat membuat laporan dari dashboard mereka.
   - **Manager** hanya bisa melihat laporan dari user di bawahnya.
   - **Admin** bisa melihat semua laporan.

**Password untuk login "password"**
