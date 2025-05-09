<?php include('../includes/db.php'); // Koneksi ke database ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Airdrop JKNymous</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
  [x-cloak] { 
    display: none !important; 
  }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col"
  x-data="{showModal: false, editId: null, editNama: '', editLink1: '', editLink2: '', editTipe: '', showDeleteModal: false, deleteId: null, showDropModal: false, showUpModal: false, dropId: null}">

  <!-- Judul di tengah -->
  <div class="text-center py-6 sticky top-0 bg-gray-100 z-20">
    <h1 class="text-2xl font-bold">Airdrop JKNymous</h1>
  </div>

  <!-- Tabel Airdrop -->
  <div class="container mx-auto px-4 overflow-y-auto max-h-[500px]">
      <table class="w-3/4 mx-auto min-w-max table-auto bg-white border border-gray-300 rounded-lg">
        <thead class="bg-gray-200 sticky -top-0.5 z-10 bg-gray-200 shadow-md">
          <tr>
            <th class="py-2 px-4 text-left">No</th> <!-- Kolom Nomor -->
            <th class="py-2 px-4 text-left">Nama Airdrop</th>
            <th class="py-2 px-4 text-left">Link 1</th>
            <th class="py-2 px-4 text-left">Link 2</th>
            <th class="py-2 px-4 text-left">Tipe</th>
            <th class="py-2 px-4 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // Query untuk mengambil data, urutkan berdasarkan Tipe dan ID (submission order)
            $sql = "SELECT * FROM airdrop_data ORDER BY is_dropped ASC, FIELD(tipe, 'Daily', 'Weekly', 'Galxe', 'Once'), id ASC";
            $result = $conn->query($sql);
            // Counter untuk nomor urut
            $no = 1;
            // Tampilkan data dalam tabel
            while ($row = $result->fetch_assoc()):
          ?>
          <tr class="border-t <?php echo $row['is_dropped'] ? 'bg-green-100' : ''; ?>">
            <td class="py-2 px-4"><?php echo $no++; ?></td> <!-- Kolom Nomor -->
            <td class="py-2 px-4"><?php echo htmlspecialchars($row['nama_airdrop']); ?></td>
            <td class="py-2 px-4">
              <a href="<?php echo htmlspecialchars($row['link_1']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700 no-underline">
                Link 1
              </a>
            </td>
            <!-- Cek apakah Link 2 ada, jika kosong jangan tampilkan data di kolom Link 2 -->
            <?php if (!empty($row['link_2'])): ?>
              <td class="py-2 px-4">
                <a href="<?php echo htmlspecialchars($row['link_2']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700 no-underline">
                  Link 2
                </a>
              </td>
            <?php else: ?>
              <td class="py-2 px-4"></td> <!-- Kosongkan cell jika Link 2 tidak ada -->
            <?php endif; ?>
            <td class="py-2 px-4"><?php echo htmlspecialchars($row['tipe']); ?></td>
            <td class="py-2 px-4 space-x-2">
              <button @click="editId=<?php echo $row['id']; ?>; editNama='<?php echo htmlspecialchars($row['nama_airdrop'], ENT_QUOTES); ?>'; editLink1='<?php echo htmlspecialchars($row['link_1'], ENT_QUOTES); ?>'; editLink2='<?php echo htmlspecialchars($row['link_2'], ENT_QUOTES); ?>'; editTipe='<?php echo $row['tipe']; ?>'; showModal = true" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
              <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" @click="deleteId = <?php echo $row['id']; ?>; showDeleteModal = true"> Delete </button>
              <?php if ($row['is_dropped'] == 0): ?>
                <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" @click="dropId = <?php echo $row['id']; ?>; showDropModal = true">Drop</button>
              <?php else: ?>
                <button class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800" @click="dropId = <?php echo $row['id']; ?>; showUpModal = true">Up</button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Modal Edit -->
      <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Edit Airdrop</h2>
        <form action="update.php" method="POST" class="space-y-3">
          <input type="hidden" name="id" :value="editId">
          <input type="text" name="nama_airdrop" class="w-full border px-3 py-1 rounded" :value="editNama" required>
          <input type="url" name="link_1" class="w-full border px-3 py-1 rounded" :value="editLink1" required>
          <input type="url" name="link_2" class="w-full border px-3 py-1 rounded" :value="editLink2">
          <select name="tipe" class="w-full border px-3 py-1 rounded" :value="editTipe" required>
            <option value="Daily">Daily</option>
            <option value="Weekly">Weekly</option>
            <option value="Galxe">Galxe</option>
            <option value="Once">Once</option>
          </select>
          <div class="flex justify-end gap-2">
            <button type="button" @click="showModal = false" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-1 rounded">Batal</button>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">Simpan</button>
          </div>
        </form>
      </div>
      </div>

      <!-- Modal DROP -->
      <div x-show="showDropModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-md shadow-md w-96 text-center">
        <h2 class="text-xl font-semibold mb-4">Yakin ingin menurunkan data ini?</h2>
        <div class="flex justify-center space-x-4">
          <form method="POST" action="drop.php">
            <input type="hidden" name="id" :value="dropId">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ya, Turunkan</button>
          </form>
          <button @click="showDropModal = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
        </div>
      </div>
      </div>

      <!-- Modal UP -->
      <div x-show="showUpModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-md shadow-md w-96 text-center">
        <h2 class="text-xl font-semibold mb-4">Yakin ingin mengembalikan data ini ke atas?</h2>
        <div class="flex justify-center space-x-4">
          <form method="POST" action="up.php">
            <input type="hidden" name="id" :value="dropId">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ya, Kembalikan</button>
          </form>
          <button @click="showUpModal = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
        </div>
      </div>
      </div>
  </div>

  <!-- Modal Delete -->
  <div x-show="showDeleteModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-md shadow-md w-96 text-center">
      <h2 class="text-xl font-semibold mb-4">Yakin ingin menghapus data ini?</h2>
      <div class="flex justify-center space-x-4">
        <form method="POST" action="delete.php">
          <input type="hidden" name="id" :value="deleteId">
          <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"> Hapus </button>
        </form>
        <button @click="showDeleteModal = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"> Batal </button>
      </div>
    </div>
  </div>

  <!-- Form di bawah -->
  <form action="submit.php" method="POST" class="bg-white p-4 w-3/4 mx-auto shadow-md rounded-md fixed bottom-10 left-1/2 transform -translate-x-1/2">
    <div class="flex flex-col sm:flex-row sm:space-x-4 gap-4">
      <input type="text" name="nama_airdrop" placeholder="Nama Airdrop" class="flex-1 border rounded px-2 py-1" required>
      <input type="url" name="link_1" placeholder="Link 1" class="flex-1 border rounded px-2 py-1" required>
      <input type="url" name="link_2" placeholder="Link 2" class="flex-1 border rounded px-2 py-1">
      <select name="tipe" class="flex-none border rounded px-2 py-1" required>
        <option value="">Tipe</option>
        <option value="Daily">Daily</option>
        <option value="Weekly">Weekly</option>
        <option value="Galxe">Galxe</option>
        <option value="Once">Once</option>
      </select>
      <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">Submit</button>
    </div>
  </form>
</body>
</html>
