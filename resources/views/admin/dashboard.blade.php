<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - كلية الصيدلة جامعة الزقازيق</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex flex-col">
        <header class="bg-zu-blue text-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">لوحة تحكم النظام - صيدلة الزقازيق</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-zu-maroon px-4 py-2 rounded text-sm hover:bg-opacity-90">تسجيل الخروج</button>
                </form>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">أهلاً بك، {{ auth()->user()->name }}</h2>
                <p class="text-gray-600">تم تسجيل دخولك بنجاح إلى لوحة تحكم الإدارة. النظام جاهز لإدارة الحجوزات والمخزون.</p>
            </div>
        </main>
    </div>
</body>
</html>