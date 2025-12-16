<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

</body>

<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

        <!-- Left image panel -->
        <div class="hidden md:flex items-center justify-center bg-gray-100 p-8">
            <div class="w-full h-full max-w-lg">
                <img src="{{ asset('images/pandawa.png') }}" alt="Pandawa" class="w-full h-full object-contain rounded-3xl">
            </div>
        </div>

        <!-- Right form panel (full right side, no inner card) -->
        <div class="flex items-center justify-center p-10">
            <div class="relative w-full h-full bg-white rounded-l-3xl p-16">

                <!-- Top-right logo -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="absolute top-6 right-6 h-12 w-auto object-contain">

                <!-- Heading -->
                <div class="mb-8">
                    <h1 class="text-4xl font-extrabold mb-2">Holla,</h1>
                    <h2 class="text-3xl font-black text-gray-900">Welcome Back Admin!</h2>
                    <p class="mt-3 text-sm text-gray-500">Let's get started with management this page again! I know you will be amazing today!</p>
                </div>

                {{-- ERROR LOGIN --}}
                @if($errors->any())
                <div class="mb-4 text-red-600">
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- FORM LOGIN DINAMIS --}}
                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    {{-- USERNAME --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="off"
                            class="w-full bg-white text-gray-800 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500/30 focus:border-yellow-500"/>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input
                            name="password"
                            type="password"
                            required
                            autocomplete="off"
                            class="w-full bg-white text-gray-800 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500/30 focus:border-yellow-500"/>
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="flex items-center mb-6">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 text-yellow-500 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">Remember me!</label>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mb-4">
                        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 rounded-xl">Login</button>
                    </div>

                    <div class="text-center">
                        <a href="{{ url('/') }}" class="text-sm text-gray-500">Kembali ke Beranda</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>