<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Buat Akun Baru</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap / Penanggung Jawab</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full mt-1 p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full mt-1 p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Daftar Sebagai</label>
                <select name="role" id="role-select" onchange="toggleOrgField()" class="w-full mt-1 p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Pembeli Tiket (Customer)</option>
                    <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Penyelenggara Event (Ormawa / HIMA / UKM)</option>
                </select>
            </div>

            <div id="org-field" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Nama Organisasi / Ormawa</label>
                <input type="text" name="organization_name" value="{{ old('organization_name') }}" placeholder="Contoh: HIMA Sistem Informasi / BEM" class="w-full mt-1 p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-indigo-50/50">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full mt-1 p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full mt-1 p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition duration-200">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Sudah punya akun? <a href="/admin/login" class="text-indigo-600 font-semibold hover:underline">Login di sini</a>
        </p>
    </div>

    <script>
        function toggleOrgField() {
            const role = document.getElementById('role-select').value;
            const orgField = document.getElementById('org-field');
            if (role === 'organizer') {
                orgField.classList.remove('hidden');
            } else {
                orgField.classList.add('hidden');
            }
        }
        // Jalankan saat pertama load jika ada old input
        toggleOrgField();
    </script>
</body>
</html>