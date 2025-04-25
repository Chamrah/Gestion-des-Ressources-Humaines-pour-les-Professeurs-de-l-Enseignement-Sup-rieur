<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body dir="rtl">

    <div class="container mt-5">
        <h1 class="mb-4 text-center">نظام إدارة الموارد البشرية</h1>

        {{-- المحتوى من الصفحات الفرعية --}}
        @yield('content')
    </div>

</body>
</html>
