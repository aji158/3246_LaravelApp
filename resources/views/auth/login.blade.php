<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-900 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-white text-slate-900 rounded-[2rem] p-8 shadow-2xl">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                AH
            </div>

            <h1 class="text-2xl font-black">Admin Login</h1>
            <p class="text-slate-500">AmikomEventHub Dashboard</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6 text-center">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">

            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl"
                    required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl"
                    required>
            </div>

            <button
                type="submit"
                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xl">
                Masuk
            </button>

        </form>

    </div>

</body>

</html>