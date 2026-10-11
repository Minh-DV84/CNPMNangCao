<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SinhVien;
use App\Models\LopHoc;

class SinhVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lops = LopHoc::whereIn('ma_lop', ['LH-01', 'LH-02', 'LH-03', 'LH-04', 'LH-05', 'LH-06'])
            ->pluck('id', 'ma_lop')
            ->toArray();

        $sinhViens = [
            // LH-01 (68MHT1)
            ['msv' => '1011068', 'ho_ten' => 'Nguyễn Văn An', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-03-15', 'lop_hoc_id' => $lops['LH-01'], 'so_dien_thoai' => '0912345101'],
            ['msv' => '0110168', 'ho_ten' => 'Trần Thị Bình', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-05-20', 'lop_hoc_id' => $lops['LH-01'], 'so_dien_thoai' => '0912345102'],
            ['msv' => '1101068', 'ho_ten' => 'Lê Văn Cường', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-07-10', 'lop_hoc_id' => $lops['LH-01'], 'so_dien_thoai' => '0912345103'],
            ['msv' => '0011068', 'ho_ten' => 'Phạm Thị Dung', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-01-25', 'lop_hoc_id' => $lops['LH-01'], 'so_dien_thoai' => '0912345104'],
            ['msv' => '1001068', 'ho_ten' => 'Hoàng Văn Em', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-09-12', 'lop_hoc_id' => $lops['LH-01'], 'so_dien_thoai' => '0912345105'],

            // LH-02 (68MHT2)
            ['msv' => '1011168', 'ho_ten' => 'Đặng Văn Giáp', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-02-08', 'lop_hoc_id' => $lops['LH-02'], 'so_dien_thoai' => '0912345201'],
            ['msv' => '0111168', 'ho_ten' => 'Vũ Thị Hà', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-06-30', 'lop_hoc_id' => $lops['LH-02'], 'so_dien_thoai' => '0912345202'],
            ['msv' => '1101168', 'ho_ten' => 'Bùi Văn Hùng', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-08-22', 'lop_hoc_id' => $lops['LH-02'], 'so_dien_thoai' => '0912345203'],
            ['msv' => '0011168', 'ho_ten' => 'Ngô Thị Khánh', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-04-18', 'lop_hoc_id' => $lops['LH-02'], 'so_dien_thoai' => '0912345204'],
            ['msv' => '1001168', 'ho_ten' => 'Đỗ Văn Long', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-11-05', 'lop_hoc_id' => $lops['LH-02'], 'so_dien_thoai' => '0912345205'],

            // LH-03 (68MHT3)
            ['msv' => '1011268', 'ho_ten' => 'Huỳnh Văn Minh', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-01-14', 'lop_hoc_id' => $lops['LH-03'], 'so_dien_thoai' => '0912345301'],
            ['msv' => '0111268', 'ho_ten' => 'Lý Thị Ngọc', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-03-28', 'lop_hoc_id' => $lops['LH-03'], 'so_dien_thoai' => '0912345302'],
            ['msv' => '1101268', 'ho_ten' => 'Phan Văn Quân', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-07-16', 'lop_hoc_id' => $lops['LH-03'], 'so_dien_thoai' => '0912345303'],
            ['msv' => '0011268', 'ho_ten' => 'Trương Thị Phương', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-09-09', 'lop_hoc_id' => $lops['LH-03'], 'so_dien_thoai' => '0912345304'],
            ['msv' => '1001268', 'ho_ten' => 'Vương Văn Quỳnh', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-12-20', 'lop_hoc_id' => $lops['LH-03'], 'so_dien_thoai' => '0912345305'],

            // LH-04 (68PM1)
            ['msv' => '1011368', 'ho_ten' => 'Dương Văn Sơn', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-02-19', 'lop_hoc_id' => $lops['LH-04'], 'so_dien_thoai' => '0912345401'],
            ['msv' => '0111368', 'ho_ten' => 'Lâm Thị Tâm', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-05-11', 'lop_hoc_id' => $lops['LH-04'], 'so_dien_thoai' => '0912345402'],
            ['msv' => '1101368', 'ho_ten' => 'Mai Văn Thắng', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-08-03', 'lop_hoc_id' => $lops['LH-04'], 'so_dien_thoai' => '0912345403'],
            ['msv' => '0011368', 'ho_ten' => 'Trịnh Thị Uyên', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-10-27', 'lop_hoc_id' => $lops['LH-04'], 'so_dien_thoai' => '0912345404'],
            ['msv' => '1001368', 'ho_ten' => 'Ninh Văn Vinh', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-06-15', 'lop_hoc_id' => $lops['LH-04'], 'so_dien_thoai' => '0912345405'],

            // LH-05 (68PM2)
            ['msv' => '1011468', 'ho_ten' => 'Ông Văn Xuân', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-03-23', 'lop_hoc_id' => $lops['LH-05'], 'so_dien_thoai' => '0912345501'],
            ['msv' => '0111468', 'ho_ten' => 'Thịnh Thị Yến', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-07-07', 'lop_hoc_id' => $lops['LH-05'], 'so_dien_thoai' => '0912345502'],
            ['msv' => '1101468', 'ho_ten' => 'Trịnh Văn Zũng', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-09-19', 'lop_hoc_id' => $lops['LH-05'], 'so_dien_thoai' => '0912345503'],
            ['msv' => '0011468', 'ho_ten' => 'Nguyễn Thị Ánh', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-11-30', 'lop_hoc_id' => $lops['LH-05'], 'so_dien_thoai' => '0912345504'],
            ['msv' => '1001468', 'ho_ten' => 'Lê Văn Bách', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-04-05', 'lop_hoc_id' => $lops['LH-05'], 'so_dien_thoai' => '0912345505'],

            // LH-06 (68PM3)
            ['msv' => '1011568', 'ho_ten' => 'Phạm Văn Cảnh', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-01-10', 'lop_hoc_id' => $lops['LH-06'], 'so_dien_thoai' => '0912345601'],
            ['msv' => '0111568', 'ho_ten' => 'Hoàng Thị Diễm', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-06-24', 'lop_hoc_id' => $lops['LH-06'], 'so_dien_thoai' => '0912345602'],
            ['msv' => '1101568', 'ho_ten' => 'Đặng Văn Giang', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-08-31', 'lop_hoc_id' => $lops['LH-06'], 'so_dien_thoai' => '0912345603'],
            ['msv' => '0011568', 'ho_ten' => 'Vũ Thị Hạnh', 'gioi_tinh' => 'Nữ', 'ngay_sinh' => '2005-10-14', 'lop_hoc_id' => $lops['LH-06'], 'so_dien_thoai' => '0912345604'],
            ['msv' => '1001568', 'ho_ten' => 'Bùi Văn Khoa', 'gioi_tinh' => 'Nam', 'ngay_sinh' => '2005-12-02', 'lop_hoc_id' => $lops['LH-06'], 'so_dien_thoai' => '0912345605'],
        ];

        foreach ($sinhViens as $sv) {
            SinhVien::updateOrCreate(
                ['msv' => $sv['msv']],
                $sv
            );
        }
    }
}
