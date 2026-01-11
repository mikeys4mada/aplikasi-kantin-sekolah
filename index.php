<?php
$barangFile = "data_barang.json";
$jualFile   = "data_penjualan.json";

$barang = file_exists($barangFile) ? json_decode(file_get_contents($barangFile), true) : [];
$penjualan = file_exists($jualFile) ? json_decode(file_get_contents($jualFile), true) : [];

/* PEMBELIAN */
if (isset($_POST['tambah_barang'])) {
    $barang[] = [
        "nama"  => $_POST['nama'],
        "stok"  => $_POST['stok'],
        "harga" => $_POST['harga']
    ];
    file_put_contents($barangFile, json_encode($barang));
}

/* PENJUALAN */
if (isset($_POST['simpan_jual'])) {
    // Parse Rupiah string to number
    $total_bayar = preg_replace('/[^\d]/', '', $_POST['total_bayar']);
    $penjualan[] = [
        "tanggal" => date("d-m-Y"),
        "total"   => (float)$total_bayar
    ];
    file_put_contents($jualFile, json_encode($penjualan));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kantin Sekolah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <h2>KANTIN</h2>
    <button onclick="showMenu('pembelian')">
        <i class="fas fa-box-open"></i>
        <span>Pembelian</span>
    </button>
    <button onclick="showMenu('penjualan')">
        <i class="fas fa-cash-register"></i>
        <span>Penjualan</span>
    </button>
    <button onclick="showMenu('laporan')">
        <i class="fas fa-chart-bar"></i>
        <span>Laporan</span>
    </button>
</div>

<div class="content">
    <h1><i class="fas fa-utensils"></i> Aplikasi Kantin Sekolah</h1>

    <!-- PEMBELIAN -->
    <div class="card" id="pembelian">
        <h3>Form Barang Masuk</h3>
        <form method="post">
            <input type="text" name="nama" placeholder="Nama Barang" required>
            <input type="number" name="stok" placeholder="Jumlah Stok" required>
            <input type="number" name="harga" placeholder="Harga Jual" required>
            <button class="btn" name="tambah_barang">Simpan</button>
        </form>
    </div>

    <!-- PENJUALAN -->
    <div class="card" id="penjualan">
        <h3>Form Penjualan</h3>
        <form method="post" id="formPenjualan">
            <div class="form-group">
                <label for="total">Total Belanja</label>
                <div class="input-group">
                    <span class="currency-symbol">Rp</span>
                    <input type="text" id="total" class="rupiah-input" placeholder="0" data-raw-value="0">
                </div>
            </div>
            
            <div class="form-group">
                <label for="ppn">PPN 10%</label>
                <div class="input-group">
                    <span class="currency-symbol">Rp</span>
                    <input type="text" id="ppn" class="rupiah-input" placeholder="0" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label for="diskon">Diskon (%)</label>
                <div class="input-group">
                    <span class="currency-symbol">%</span>
                    <input type="text" id="diskon" class="rupiah-input" placeholder="0" value="0" style="padding-left: 25px;">
                </div>
                <div class="discount-amount" style="font-size: 12px; color: #666; margin-top: 4px;">
                    Jumlah Diskon: <span id="diskon-amount">Rp 0</span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="totalBayar">Total Bayar</label>
                <div class="input-group">
                    <span class="currency-symbol">Rp</span>
                    <input type="text" id="totalBayar" name="total_bayar" class="rupiah-input" placeholder="0" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label for="bayar">Dibayar</label>
                <div class="input-group">
                    <span class="currency-symbol">Rp</span>
                    <input type="text" id="bayar" class="rupiah-input" placeholder="0" data-raw-value="0">
                </div>
            </div>
            
            <div class="form-group">
                <label for="kembali">Kembalian</label>
                <div class="input-group">
                    <span class="currency-symbol">Rp</span>
                    <input type="text" id="kembali" class="rupiah-input" placeholder="0" readonly>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn" onclick="hitung()">
                    <i class="fas fa-calculator"></i> Hitung
                </button>
                <button class="btn green" name="simpan_jual">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <!-- LAPORAN -->
    <div class="card" id="laporan">
        <h3>Laporan Stok Barang</h3>
        <table>
            <tr>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Harga Jual</th>
            </tr>
            <?php foreach ($barang as $b): ?>
            <tr>
                <td><?= $b['nama'] ?></td>
                <td><?= $b['stok'] ?></td>
                <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Laporan Penjualan</h3>
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
            <?php foreach ($penjualan as $p): ?>
            <tr>
                <td><?= $p['tanggal'] ?></td>
                <td>Rp <?= number_format($p['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script src="main.js"></script>
</body>
</html>
