<?php

namespace App\Controllers;

/**
 * Auth Controller - Manajer Autentikasi
 * 
 * Controller ini bertanggung jawab mengelola proses autentikasi pengguna,
 * termasuk login, logout, dan manajemen session.
 * Merupakan bagian dari pola arsitektur MVC sebagai "Manajer" (Controller).
 * 
 * @package App\Controllers
 * @author  KlinPro Development Team
 */
class Auth extends BaseController
{
    /**
     * Kredensial admin default
     * Dalam produksi, ini akan diambil dari database
     */
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'bisnis123';

    /**
     * Menampilkan halaman login
     * 
     * Jika user sudah login (session aktif), otomatis redirect ke dashboard.
     * Jika belum, tampilkan halaman login.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        // Jika sudah login, tendang ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Ambil flash message error jika ada
        $data['error'] = session()->getFlashdata('error');

        return view('login_view', $data);
    }

    /**
     * Memproses form login
     * 
     * Mengambil data username dan password dari form POST,
     * memvalidasi kredensial, dan mengatur session jika berhasil.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function process()
    {
        // 1. Ambil 'username' dan 'password' dari form POST
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 2. Validasi kredensial
        if ($username === self::ADMIN_USERNAME && $password === self::ADMIN_PASSWORD) {
            // 3. Jika BENAR: Set session data dan redirect ke dashboard
            $sessionData = [
                'isLoggedIn'  => true,
                'username'    => $username,
                'role'        => 'admin',
                'login_time'  => date('Y-m-d H:i:s'),
            ];
            session()->set($sessionData);

            return redirect()->to('/dashboard')->with('success', 'Selamat datang, Admin!');
        }

        // 4. Jika SALAH: Redirect kembali ke halaman login dengan pesan error
        return redirect()->to('/')->with('error', 'Username atau password salah! Silakan coba lagi.');
    }

    /**
     * Proses logout pengguna
     * 
     * Menghancurkan semua data session dan mengarahkan kembali ke halaman login.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        // 1. Hancurkan session
        session()->destroy();

        // 2. Redirect ke halaman login
        return redirect()->to('/');
    }
}