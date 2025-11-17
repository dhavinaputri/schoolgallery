# 🏫 School Gallery

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![React](https://img.shields.io/badge/React-19.x-61DAFB?style=for-the-badge&logo=react&logoColor=black)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Inertia.js](https://img.shields.io/badge/Inertia.js-2.x-9553E9?style=for-the-badge&logo=inertia&logoColor=white)

**Aplikasi Galeri Sekolah dengan sistem manajemen berita dan galeri foto yang modern**

[📖 Dokumentasi API](#-api-documentation) • [🚀 Instalasi](#-instalasi) • [✨ Fitur](#-fitur) • [📱 Demo](#-demo)

</div>

---

## 📋 Tentang Proyek

**School Gallery** adalah aplikasi web modern yang dibangun dengan Laravel 12 dan React 19, dirancang khusus untuk sekolah-sekolah yang ingin mempublikasikan galeri foto dan berita sekolah secara online. Aplikasi ini menyediakan antarmuka admin yang intuitif untuk mengelola konten dan API yang lengkap untuk integrasi dengan aplikasi lain.

### 🎯 Tujuan
- Memudahkan sekolah dalam mempublikasikan galeri foto kegiatan
- Menyediakan platform berita sekolah yang mudah dikelola
- Memberikan API yang lengkap untuk integrasi dengan sistem lain
- Menyediakan antarmuka yang responsif dan modern

---

## ✨ Fitur

### 🖼️ **Galeri Foto**
- 📸 Upload dan kelola foto galeri dengan kategori
- 🏷️ Sistem kategori untuk mengorganisir foto
- 📱 Tampilan responsif dengan grid layout
- 🔍 Fitur pencarian dan filter
- 📥 Download foto dalam resolusi asli
- 👁️ Preview foto dengan lightbox

### 📰 **Sistem Berita**
- ✍️ Editor berita dengan upload gambar
- 🏷️ Kategori berita yang dapat dikustomisasi
- 📅 Sistem publish/unpublish berita
- 🔗 URL SEO-friendly dengan slug otomatis
- 📊 Statistik pembaca dan engagement

### 👨‍💼 **Panel Admin**
- 🔐 Sistem autentikasi dengan Laravel Sanctum
- 👥 Manajemen admin dengan role-based access
- 📊 Dashboard dengan statistik lengkap
- 🔄 Reset password dengan email notification
- 🎨 Antarmuka modern dengan React + TailwindCSS

### 🔌 **API Lengkap**
- 🌐 RESTful API untuk semua fitur
- 🔑 Autentikasi dengan Bearer Token
- 📄 Dokumentasi API yang lengkap
- 🔍 Filter dan pencarian melalui API
- 📱 Response format JSON yang konsisten

### 🎨 **Frontend Modern**
- ⚛️ React 19 dengan Inertia.js
- 🎨 TailwindCSS 4.x untuk styling
- 📱 Fully responsive design
- 🚀 Vite untuk build tool yang cepat
- 🎯 Component-based architecture

---

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12.x** - PHP Framework
- **Laravel Sanctum** - API Authentication
- **MySQL/PostgreSQL** - Database
- **Laravel Inertia** - SPA Integration

### Frontend
- **React 19.x** - JavaScript Library
- **Inertia.js 2.x** - SPA Framework
- **TailwindCSS 4.x** - CSS Framework
- **Vite 7.x** - Build Tool
- **Lucide React** - Icon Library

### Development Tools
- **Laravel Pint** - Code Style Fixer
- **PHPUnit** - Testing Framework
- **Laravel Pail** - Log Viewer
- **Concurrently** - Process Manager

---

## 🚀 Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ dan npm
- MySQL/PostgreSQL
- Git

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/schoolgallery.git
cd schoolgallery
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Konfigurasi Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schoolgallery
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Jalankan Migration dan Seeder
```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder untuk data awal
php artisan db:seed
```

### 6. Setup Storage Link
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Jalankan Aplikasi
```bash
# Jalankan server Laravel
php artisan serve

# Atau gunakan script dev yang sudah dikonfigurasi
composer run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## 🔑 Login Admin

Setelah menjalankan seeder, Anda dapat login dengan kredensial berikut:

```
Email: admin@schoolgallery.com
Password: password
```

**⚠️ Penting:** Ganti password default setelah login pertama kali!

---

## 📖 API Documentation

Aplikasi ini menyediakan API yang lengkap untuk integrasi dengan aplikasi lain. Dokumentasi lengkap dapat dilihat di [API_DOCUMENTATION.md](./API_DOCUMENTATION.md).

### Endpoint Utama:
- `GET /api/v1/galleries` - Daftar galeri
- `GET /api/v1/news` - Daftar berita
- `POST /api/v1/admin/login` - Login admin
- `GET /api/v1/school-profile` - Profil sekolah

### Contoh Request:
```bash
# Get all galleries
curl -X GET "http://localhost:8000/api/v1/galleries" \
  -H "Accept: application/json"

# Login admin
curl -X POST "http://localhost:8000/api/v1/admin/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@schoolgallery.com","password":"password"}'
```

---

## 📁 Struktur Proyek

```
schoolgallery/
├── 📁 app/
│   ├── 📁 Http/Controllers/     # Controller untuk web dan API
│   ├── 📁 Models/              # Eloquent Models
│   ├── 📁 Notifications/       # Email Notifications
│   └── 📁 Providers/           # Service Providers
├── 📁 database/
│   ├── 📁 migrations/          # Database migrations
│   └── 📁 seeders/            # Database seeders
├── 📁 resources/
│   ├── 📁 js/                 # React components dan assets
│   │   ├── 📁 components/     # Reusable components
│   │   ├── 📁 Layouts/        # Layout components
│   │   └── 📁 Pages/          # Page components
│   ├── 📁 views/              # Blade templates
│   └── 📁 css/                # CSS files
├── 📁 routes/
│   ├── 📄 web.php             # Web routes
│   └── 📄 api.php             # API routes
├── 📁 public/                 # Public assets
└── 📁 storage/                # File storage
```

---

## 🎨 Customization

### Mengubah Tema
Aplikasi menggunakan TailwindCSS yang dapat dengan mudah dikustomisasi:

1. Edit file `tailwind.config.js`
2. Modifikasi warna, font, dan spacing sesuai kebutuhan
3. Rebuild assets dengan `npm run build`

### Menambah Fitur
1. Buat migration untuk tabel baru
2. Buat model dan controller
3. Tambahkan routes di `web.php` atau `api.php`
4. Buat komponen React untuk frontend
5. Update dokumentasi API

---

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Jalankan test dengan coverage
php artisan test --coverage

# Jalankan test specific
php artisan test --filter=NewsTest
```

---

## 📊 Performance

### Optimasi yang Sudah Diterapkan:
- ⚡ **Lazy Loading** untuk gambar galeri
- 🗄️ **Database Indexing** untuk query yang cepat
- 📦 **Asset Optimization** dengan Vite
- 🖼️ **Image Optimization** dengan resize otomatis
- 💾 **Caching** untuk data yang jarang berubah

### Rekomendasi Server:
- **RAM:** Minimal 2GB
- **CPU:** 2 Core
- **Storage:** SSD dengan minimal 10GB
- **PHP:** 8.2+ dengan OPcache enabled

---

## 🔒 Security

### Fitur Keamanan:
- 🔐 **Laravel Sanctum** untuk API authentication
- 🛡️ **CSRF Protection** untuk form
- 🔒 **Password Hashing** dengan bcrypt
- 🚫 **SQL Injection Protection** dengan Eloquent ORM
- 📧 **Email Verification** untuk admin baru
- 🔑 **Rate Limiting** untuk API endpoints

### Best Practices:
- Selalu gunakan HTTPS di production
- Update dependencies secara berkala
- Backup database secara rutin
- Monitor log aplikasi
- Gunakan environment variables untuk konfigurasi sensitif

---

## 🚀 Deployment

### Production Checklist:
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Konfigurasi database production
- [ ] Setup email configuration
- [ ] Jalankan `php artisan config:cache`
- [ ] Jalankan `php artisan route:cache`
- [ ] Jalankan `php artisan view:cache`
- [ ] Build assets dengan `npm run build`
- [ ] Setup web server (Nginx/Apache)
- [ ] Setup SSL certificate

### Docker Deployment (Opsional):
```bash
# Build Docker image
docker build -t schoolgallery .

# Run container
docker run -p 8000:8000 schoolgallery
```

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. 🍴 Fork repository ini
2. 🌿 Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. 💾 Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. 📤 Push ke branch (`git push origin feature/AmazingFeature`)
5. 🔄 Buat Pull Request

### Coding Standards:
- Ikuti PSR-12 untuk PHP
- Gunakan Laravel Pint untuk code formatting
- Tulis test untuk fitur baru
- Update dokumentasi jika diperlukan

---

## 📝 Changelog

### v1.0.0 (2024-01-XX)
- ✨ Initial release
- 🖼️ Gallery management system
- 📰 News management system
- 👨‍💼 Admin panel
- 🔌 RESTful API
- 📱 Responsive design

---

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE) - lihat file LICENSE untuk detail.

---

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

- 📧 **Email:** support@schoolgallery.com
- 🐛 **Issues:** [GitHub Issues](https://github.com/yourusername/schoolgallery/issues)
- 💬 **Discussions:** [GitHub Discussions](https://github.com/yourusername/schoolgallery/discussions)
- 📖 **Wiki:** [Project Wiki](https://github.com/yourusername/schoolgallery/wiki)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - PHP Framework
- [React](https://reactjs.org) - JavaScript Library
- [TailwindCSS](https://tailwindcss.com) - CSS Framework
- [Inertia.js](https://inertiajs.com) - SPA Framework
- [Lucide](https://lucide.dev) - Icon Library

---

<div align="center">

**⭐ Jika proyek ini membantu Anda, jangan lupa berikan star! ⭐**

Made with ❤️ by Dhavina
