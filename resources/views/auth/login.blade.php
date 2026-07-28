<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AmikomEventHub</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-900 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-white text-slate-900 rounded-[2rem] p-8 shadow-2xl">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                AH
            </div>

            <h1 class="text-2xl font-black">Admin Login</h1>
            <p class="text-slate-500">AmikomEventHub Dashboard</p>
        </div>

        <!-- Alert Error -->
        @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6 text-center text-sm font-semibold">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Form Login Email & Password -->
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:border-indigo-600 transition"
                    placeholder="nama@email.com"
                    required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:border-indigo-600 transition"
                    placeholder="••••••••"
                    required>
            </div>

            <button
                type="submit"
                class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-lg transition shadow-lg shadow-indigo-200">
                Masuk
            </button>
        </form>

        <!-- Pemisah ATAU -->
        <div class="relative flex py-5 items-center my-2">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-xs font-bold text-slate-400 uppercase">Atau</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <!-- Tombol Google SSO (Soal 1 Fitur 1) -->
        <a href="{{ route('auth.google') }}" 
           class="w-full py-3.5 px-4 bg-white border-2 border-slate-200 hover:bg-slate-50 text-slate-700 rounded-2xl font-bold text-base flex items-center justify-center gap-3 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.1 0-5.74-2.09-6.68-4.91H1.32v3.13C3.31 21.36 7.37 24 12 24z"/>
                <path fill="#FBBC05" d="M5.32 14.29c-.24-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.58H1.32C.48 8.26 0 10.07 0 12s.48 3.74 1.32 5.42l4-3.13z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.37 0 3.31 2.64 1.32 6.58l4 3.13c.94-2.82 3.58-4.96 6.68-4.96z"/>
            </svg>
            Continue with Google
        </a>

    </div>

</body>

</html>