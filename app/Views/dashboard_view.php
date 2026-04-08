<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($nama_startup) ?></title>
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { background-color: #2c3e50; color: white; min-height: 100vh; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 15px; }
        .sidebar a:hover { color: white; background-color: #34495e; }
        .service-card { border-radius: 10px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .status-badge { font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0 sidebar">
            <div class="p-3 text-center border-bottom border-secondary">
                <h4><?= htmlspecialchars($nama_startup) ?></h4>
                <small class="text-muted">Laundry Management</small>
            </div>
            <div class="p-3">
                <p class="mb-1"><small>Logged in as: <strong><?= htmlspecialchars($username) ?></strong></small></p>
                <a href="#" class="active">📦 Data Layanan</a>
                <a href="#">📝 Pesanan Aktif</a>
                <a href="#">👥 Pelanggan</a>
                <hr class="border-secondary">
                <a href="/auth/logout" class="text-danger">⏏️ Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            
            <?php if (isset($success) && !empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen Layanan Laundry</h2>
                <div>
                    <span class="badge bg-primary">Total Layanan: <span id="total-services"><?= count($products) ?></span></span>
                </div>
            </div>

            <!-- Interaktivitas CRUD (Tanpa SQL) -->
            <div class="card service-card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Tambah Layanan Baru (Client-side)</h5>
                </div>
                <div class="card-body">
                    <form id="add-service-form" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" id="new-name" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                            <select id="new-category" class="form-select" required>
                                <?php foreach($categories as $key => $cat): ?>
                                    <option value="<?= $key ?>"><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" id="new-price" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Estimasi Stok/Kapasitas</label>
                            <input type="number" id="new-stock" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">+ Tambah Layanan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card service-card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Katalog Tersedia</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0" id="services-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Layanan</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Kapasitas/Stok Harian</th>
                                <th>Aksi (Interaksi JS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $index => $p): ?>
                            <tr data-id="<?= $p['id'] ?>">
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <strong><?= $p['name'] ?></strong><br>
                                    <small class="text-muted"><?= $p['estimated_duration'] ?></small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= $p['category'] ?></span></td>
                                <td class="price-cell">Rp <?= number_format($p['price'], 0, ',', '.') ?> <?= $p['unit'] ?></td>
                                <td>
                                    <span class="stock-value fw-bold fs-5"><?= $p['stock'] ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-process" onclick="processOrder(this)">Proses Pesanan</button>
                                    <button class="btn btn-sm btn-danger btn-delete" onclick="deleteService(this)">Hapus</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- TUGAS MAHASISWA: DOM MANIPULATION & INTERAKTIVITAS ---

    /**
     * Fitur 1: Mengurangi stok ketika pesanan diproses
     */
    function processOrder(btnElement) {
        // Mendapatkan baris tr (table row) tempat tombol diklik
        const row = btnElement.closest('tr');
        // Mendapatkan elemen span yang menyimpan nilai stok
        const stockElement = row.querySelector('.stock-value');
        
        let currentStock = parseInt(stockElement.innerText);
        
        if (currentStock > 0) {
            // Kurangi stok
            currentStock -= 1;
            stockElement.innerText = currentStock;
            
            // Ubah tombol menjadi warna hijau sejenak untuk feedback UX
            const originalText = btnElement.innerText;
            const originalClass = btnElement.className;
            
            btnElement.innerText = 'Diproses!';
            btnElement.className = 'btn btn-sm btn-success btn-process';
            
            // Efek visual pada baris
            row.style.backgroundColor = '#e8f5e9';
            
            // Kembalikan ke warna asal setelah 1 detik
            setTimeout(() => {
                btnElement.innerText = originalText;
                btnElement.className = originalClass;
                row.style.backgroundColor = '';
            }, 1000);
            
            // Jika stok habis setelah dikurangi
            if (currentStock === 0) {
                stockElement.classList.add('text-danger');
                btnElement.disabled = true;
                btnElement.innerText = 'Kapasitas Penuh';
                btnElement.classList.replace('btn-primary', 'btn-secondary');
            }
        }
    }

    /**
     * Fitur 2: Menghapus baris tabel layanan
     */
    function deleteService(btnElement) {
        if(confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
            const row = btnElement.closest('tr');
            const serviceName = row.querySelector('strong').innerText;
            
            // Animasi fade out sederhana
            row.style.transition = "opacity 0.5s ease";
            row.style.opacity = 0;
            
            setTimeout(() => {
                row.remove();
                
                // Update total counter
                const totalSpan = document.getElementById('total-services');
                totalSpan.innerText = parseInt(totalSpan.innerText) - 1;
                
                // Update nomor urut
                updateRowNumbers();
                
                alert(`Layanan "${serviceName}" berhasil dihapus dari tampilan.`);
            }, 500);
        }
    }

    /**
     * Fungsi bantuan untuk mengupdate nomor urut pada tabel
     */
    function updateRowNumbers() {
        const tbody = document.querySelector('#services-table tbody');
        const rows = tbody.querySelectorAll('tr');
        
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    /**
     * Fitur 3: Form Penambahan Layanan Baru (Client-side Insert)
     */
    document.getElementById('add-service-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form reload halaman
        
        // Ambil nilai dari form
        const name = document.getElementById('new-name').value;
        const category = document.getElementById('new-category').value;
        const price = document.getElementById('new-price').value;
        const stock = document.getElementById('new-stock').value;
        
        // Format harga ke Rupiah sederhana
        const formattedPrice = "Rp " + parseInt(price).toLocaleString('id-ID');
        
        // Buat baris baru
        const tbody = document.querySelector('#services-table tbody');
        const count = tbody.querySelectorAll('tr').length + 1;
        
        const tr = document.createElement('tr');
        tr.style.backgroundColor = '#fff3cd'; // Highlight row baru
        
        tr.innerHTML = `
            <td>${count}</td>
            <td>
                <strong>${name}</strong><br>
                <small class="text-muted">Baru ditambahkan</small>
            </td>
            <td><span class="badge bg-warning text-dark">${category}</span></td>
            <td class="price-cell">${formattedPrice} per kg</td>
            <td>
                <span class="stock-value fw-bold fs-5">${stock}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-primary btn-process" onclick="processOrder(this)">Proses Pesanan</button>
                <button class="btn btn-sm btn-danger btn-delete" onclick="deleteService(this)">Hapus</button>
            </td>
        `;
        
        tbody.appendChild(tr);
        
        // Hilangkan highlight setelah 2 detik
        setTimeout(() => {
            tr.style.backgroundColor = '';
        }, 2000);
        
        // Update total tracker
        const totalSpan = document.getElementById('total-services');
        totalSpan.innerText = parseInt(totalSpan.innerText) + 1;
        
        // Reset form
        this.reset();
    });
</script>

</body>
</html>