<?php

namespace App\Controllers;

use App\Models\ProductModel;

/**
 * Dashboard Controller - Manajer Halaman Utama
 * 
 * Controller ini bertanggung jawab mengelola halaman dashboard utama.
 * Menerapkan proteksi session untuk memastikan hanya pengguna 
 * yang sudah login yang dapat mengakses halaman ini.
 * 
 * @package App\Controllers
 * @author  KlinPro Development Team
 */
class Dashboard extends BaseController
{
    /**
     * Instance dari ProductModel
     * @var ProductModel
     */
    private ProductModel $productModel;

    /**
     * Nama startup yang ditampilkan di dashboard
     * @var string
     */
    private string $namaStartup = 'KlinPro';

    /**
     * Constructor - Inisialisasi model
     */
    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Menampilkan halaman dashboard utama
     * 
     * Method ini melakukan pengecekan session terlebih dahulu.
     * Jika user belum login, akan di-redirect ke halaman login.
     * Jika sudah login, data layanan diambil dari Model dan dikirim ke View.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        // Proteksi halaman: Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, tendang ke halaman login
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Panggil Gudang (Model) untuk mengambil data layanan
        $products = $this->productModel->getDummyData();

        // Siapkan data untuk dikirim ke Etalase (View)
        $data = [
            'nama_startup'    => $this->namaStartup,
            'products'        => $products,
            'categories'      => $this->productModel->getCategories(),
            'total_services'  => $this->productModel->countActiveServices(),
            'username'        => session()->get('username') ?? 'Admin',
            'login_time'      => session()->get('login_time') ?? '-',
            'success'         => session()->getFlashdata('success'),
        ];

        // Tampilkan Etalase (View)
        return view('dashboard_view', $data);
    }
}