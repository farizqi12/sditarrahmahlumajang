<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Sekolah</title>
</head>
<body>
    <h1>Selamat Datang, Kepala Sekolah!</h1>
    <p>Ini adalah halaman dashboard khusus untuk Kepala Sekolah.</p>
    <x-sidebar></x-sidebar>

    <x-navbar></x-navbar>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
