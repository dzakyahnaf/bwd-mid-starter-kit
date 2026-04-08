<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProductModel - Gudang Data Layanan Laundry
 * 
 * Model ini bertanggung jawab sebagai "Gudang" data layanan laundry KlinPro.
 * Menggunakan konsep OOP dengan encapsulation untuk mengelola data layanan.
 * Saat ini menggunakan data dummy (array) karena belum terhubung ke database SQL.
 * 
 * @package App\Models
 * @author  KlinPro Development Team
 */
class ProductModel extends Model
{
    /**
     * Nama tabel yang akan digunakan saat terhubung ke database
     * @var string
     */
    protected $table = 'services';

    /**
     * Primary key dari tabel
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Field yang diizinkan untuk mass assignment
     * @var array
     */
    protected $allowedFields = [
        'service_name',
        'description', 
        'price',
        'estimated_duration',
        'category',
        'stock',
        'is_active'
    ];

    /**
     * Kategori layanan yang tersedia
     * @var array
     */
    private array $categories = [
        'cuci'    => 'Cuci & Lipat',
        'premium' => 'Premium Care',
        'express' => 'Express Service',
        'sepatu'  => 'Perawatan Sepatu',
    ];

    /**
     * Mengambil semua data dummy layanan laundry
     * 
     * Method ini mengembalikan array berisi data layanan laundry KlinPro.
     * Data ini merepresentasikan layanan yang ditawarkan oleh startup.
     * 
     * @return array Data layanan laundry dalam bentuk array asosiatif
     */
    public function getDummyData(): array
    {
        return [
            [
                'id'                 => 1,
                'name'               => 'Cuci Reguler',
                'description'        => 'Layanan cuci standar dengan deterjen premium, cocok untuk pakaian sehari-hari.',
                'price'              => 7000,
                'unit'               => 'per kg',
                'estimated_duration' => '2-3 hari',
                'category'           => 'cuci',
                'stock'              => 50,
                'is_active'          => true,
            ],
            [
                'id'                 => 2,
                'name'               => 'Cuci Express',
                'description'        => 'Layanan cuci kilat selesai dalam 6 jam, prioritas antrian utama.',
                'price'              => 15000,
                'unit'               => 'per kg',
                'estimated_duration' => '6 jam',
                'category'           => 'express',
                'stock'              => 30,
                'is_active'          => true,
            ],
            [
                'id'                 => 3,
                'name'               => 'Dry Cleaning',
                'description'        => 'Perawatan khusus untuk jas, gaun, dan pakaian formal berbahan sensitif.',
                'price'              => 35000,
                'unit'               => 'per potong',
                'estimated_duration' => '3-4 hari',
                'category'           => 'premium',
                'stock'              => 20,
                'is_active'          => true,
            ],
            [
                'id'                 => 4,
                'name'               => 'Setrika Saja',
                'description'        => 'Layanan setrika profesional untuk pakaian yang sudah bersih dan kering.',
                'price'              => 5000,
                'unit'               => 'per kg',
                'estimated_duration' => '1 hari',
                'category'           => 'cuci',
                'stock'              => 40,
                'is_active'          => true,
            ],
            [
                'id'                 => 5,
                'name'               => 'Cuci Sepatu Sneakers',
                'description'        => 'Deep cleaning sepatu sneakers dengan teknik khusus dan bahan aman.',
                'price'              => 50000,
                'unit'               => 'per pasang',
                'estimated_duration' => '3-5 hari',
                'category'           => 'sepatu',
                'stock'              => 15,
                'is_active'          => true,
            ],
            [
                'id'                 => 6,
                'name'               => 'Cuci Bed Cover & Selimut',
                'description'        => 'Pencucian khusus untuk bed cover, selimut, dan sprei berukuran besar.',
                'price'              => 25000,
                'unit'               => 'per potong',
                'estimated_duration' => '3-4 hari',
                'category'           => 'premium',
                'stock'              => 25,
                'is_active'          => true,
            ],
        ];
    }

    /**
     * Mengambil data layanan berdasarkan ID
     * 
     * @param int $id ID layanan yang dicari
     * @return array|null Data layanan atau null jika tidak ditemukan
     */
    public function getServiceById(int $id): ?array
    {
        $services = $this->getDummyData();
        foreach ($services as $service) {
            if ($service['id'] === $id) {
                return $service;
            }
        }
        return null;
    }

    /**
     * Mengambil daftar kategori layanan
     * 
     * @return array Daftar kategori
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Mengambil layanan berdasarkan kategori
     * 
     * @param string $category Nama kategori
     * @return array Data layanan yang sesuai kategori
     */
    public function getServicesByCategory(string $category): array
    {
        return array_filter($this->getDummyData(), function ($service) use ($category) {
            return $service['category'] === $category;
        });
    }

    /**
     * Menghitung total layanan aktif
     * 
     * @return int Jumlah layanan aktif
     */
    public function countActiveServices(): int
    {
        return count(array_filter($this->getDummyData(), function ($service) {
            return $service['is_active'] === true;
        }));
    }

    /**
     * Format harga ke format Rupiah
     * 
     * @param int $price Harga dalam integer
     * @return string Harga terformat
     */
    public static function formatRupiah(int $price): string
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}