<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="container max-w-4xl mx-auto p-4 bg-white shadow-md rounded-lg">
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold text-gray-700">Dashboard Siswa</h1>
      <p class="text-gray-500">Kelola transaksi dengan mudah dan cepat</p>
    </div>

    <!-- Buttons Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <!-- Topup Button -->
      <button class="p-4 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 transition">
        Top Up
      </button>

      <!-- Transfer Section -->
      <button class="p-4 bg-green-500 text-white rounded-lg shadow-lg hover:bg-green-600 transition">
        Transfer Antar Siswa
      </button>

      <!-- Withdrawal Button -->
      <button class="p-4 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition">
        Withdrawal
      </button>

      <!-- Transaction History Button -->
      <button class="p-4 bg-purple-500 text-white rounded-lg shadow-lg hover:bg-purple-600 transition">
        Riwayat Transaksi
      </button>
    </div>

    <!-- Transaction History Table -->
    <div>
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Transaksi Harian</h2>
      <table class="w-full border-collapse border border-gray-300 rounded-lg">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-3 text-left border border-gray-300">Tanggal</th>
            <th class="p-3 text-left border border-gray-300">Deskripsi</th>
            <th class="p-3 text-left border border-gray-300">Jumlah</th>
            <th class="p-3 text-left border border-gray-300">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="p-3 border border-gray-300">2025-01-21</td>
            <td class="p-3 border border-gray-300">Topup</td>
            <td class="p-3 border border-gray-300">Rp 50.000</td>
            <td class="p-3 border border-gray-300 text-green-500">Berhasil</td>
          </tr>
          <tr>
            <td class="p-3 border border-gray-300">2025-01-20</td>
            <td class="p-3 border border-gray-300">Transfer ke siswa A</td>
            <td class="p-3 border border-gray-300">Rp 30.000</td>
            <td class="p-3 border border-gray-300 text-red-500">Gagal</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
