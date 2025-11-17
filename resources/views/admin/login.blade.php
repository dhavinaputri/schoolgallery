<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Galeri Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @php
        $useVite = false;
        try {
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $json = json_decode(@file_get_contents($manifestPath), true);
                if (is_array($json)) {
                    // Only enable Vite if the expected CSS or JS entry exists in manifest
                    $useVite = isset($json['resources/css/app.css']) || isset($json['resources/js/app.js']);
                }
            }
        } catch (\Exception $e) {
            $useVite = false;
        }
    @endphp
    
    @if ($useVite)
        @vite(['resources/css/app.css'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .bg-auth-pattern {
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .input-focus-effect {
            transition: all 0.3s ease;
        }
        .input-focus-effect:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .btn-primary {
            background-image: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .logo-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
    </style>
</head>
<body class="bg-auth-pattern">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo Sekolah -->
            <div class="text-center mb-8">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg mb-4 logo-pulse">
                    <i class="fas fa-school text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Galeri Sekolah</h1>
                <p class="mt-2 text-gray-600">Sistem Manajemen Konten Sekolah</p>
            </div>

            <!-- Card Login -->
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Masuk ke Dashboard</h2>
                    <div class="h-1 w-16 bg-blue-500 mx-auto mt-2 rounded-full"></div>
                </div>


                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('status'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    {{ session('status') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="masukkan email"
                                value="{{ old('email') }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                placeholder="masukkan password">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                <i class="fas fa-eye-slash text-gray-400 hover:text-blue-600 cursor-pointer"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                <input type="checkbox" name="remember" id="remember" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-gray-300 appearance-none cursor-pointer transition-transform duration-200 ease-in-out checked:right-0 checked:border-blue-500 checked:bg-blue-500"/>
                                <label for="remember" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-200 cursor-pointer"></label>
                            </div>
                            <label for="remember" class="text-sm font-medium text-gray-700 cursor-pointer select-none">
                                Ingat saya
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('admin.password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors flex items-center">
                                <i class="fas fa-key mr-1 text-xs"></i> Lupa password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white btn-primary">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Dashboard
                        </button>
                    </div>
                    
                    <style>
                        .toggle-checkbox:checked + .toggle-label {
                            background-color: #dbeafe;
                        }
                    </style>
                </form>
            </div>

            <!-- Copyright -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p class="flex items-center justify-center">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    &copy; {{ date('Y') }} Galeri Sekolah. All rights reserved.
                </p>
                <div class="mt-2 flex justify-center space-x-4">
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Toggle Password -->
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.closest('div.relative').querySelector('input');
                const icon = this.querySelector('i');
                if(input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
</body>
</html>