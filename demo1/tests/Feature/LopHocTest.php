<?php

namespace Tests\Feature;

use App\Models\LopHoc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LopHocTest extends TestCase
{
    use RefreshDatabase;

    private function duLieuHopLe(array $ghiDe = []): array
    {
        return array_merge([
            'ten_lop' => 'Lớp 68PM1',
            'ma_lop' => '68PM1',
            'giao_vien' => 'Nguyễn Văn A',
            'so_dien_thoai_gvcn' => '0123456789',
            'ghi_chu' => null,
            'si_so' => 40,
            'trang_thai' => 1,
        ], $ghiDe);
    }

    public function test_form_them_moi_hien_thi_duoc(): void
    {
        $this->get(route('lophoc.create'))->assertOk()->assertSee('Thêm lớp học');
    }

    public function test_nhap_sai_thi_giu_lai_du_lieu_cu_va_hien_loi_tai_o(): void
    {
        $this->from(route('lophoc.create'))
            ->followingRedirects()
            ->post(route('lophoc.store'), $this->duLieuHopLe(['ten_lop' => 'Lớp giữ lại', 'si_so' => 0]))
            ->assertSee('Lớp giữ lại')
            ->assertSee('Sĩ số phải từ 1 trở lên.')
            ->assertSee('is-invalid');
    }

    public function test_them_lop_hoc_hop_le(): void
    {
        $this->post(route('lophoc.store'), $this->duLieuHopLe())
            ->assertRedirect(route('lophoc.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('lop_hocs', ['ma_lop' => '68PM1', 'si_so' => 40]);
    }

    public function test_form_rong_bao_loi_cac_truong_bat_buoc(): void
    {
        $this->from(route('lophoc.create'))
            ->post(route('lophoc.store'), [])
            ->assertRedirect(route('lophoc.create'))
            ->assertSessionHasErrors(['ten_lop', 'ma_lop', 'giao_vien', 'si_so', 'trang_thai'])
            ->assertSessionDoesntHaveErrors(['so_dien_thoai_gvcn', 'ghi_chu']);

        $this->assertDatabaseCount('lop_hocs', 0);
    }

    public function test_thong_bao_loi_bang_tieng_viet(): void
    {
        LopHoc::factory()->create(['ma_lop' => 'TRUNG']);

        $this->post(route('lophoc.store'), $this->duLieuHopLe(['ma_lop' => 'TRUNG', 'si_so' => 0, 'ten_lop' => '']))
            ->assertSessionHasErrors([
                'ten_lop' => 'Tên lớp không được để trống.',
                'ma_lop' => 'Mã lớp này đã tồn tại.',
                'si_so' => 'Sĩ số phải từ 1 trở lên.',
            ]);
    }

    public function test_ma_lop_qua_6_ky_tu_bi_tu_choi(): void
    {
        $this->post(route('lophoc.store'), $this->duLieuHopLe(['ma_lop' => '68PM123']))
            ->assertSessionHasErrors(['ma_lop' => 'Mã lớp không được vượt quá 6 ký tự.']);
    }

    public function test_ma_lop_duoc_viet_hoa_truoc_khi_luu(): void
    {
        $this->post(route('lophoc.store'), $this->duLieuHopLe(['ma_lop' => 'pm01a']));

        $this->assertDatabaseHas('lop_hocs', ['ma_lop' => 'PM01A']);
    }

    public function test_form_sua_hien_du_lieu_cu(): void
    {
        $lopHoc = LopHoc::factory()->create(['ten_lop' => 'Lớp cần sửa']);

        $this->get(route('lophoc.edit', $lopHoc))
            ->assertOk()
            ->assertSee('Lớp cần sửa');
    }

    public function test_sua_giu_nguyen_ma_lop_cua_chinh_no(): void
    {
        $lopHoc = LopHoc::factory()->create(['ma_lop' => 'AB001']);

        $this->put(route('lophoc.update', $lopHoc), $this->duLieuHopLe(['ma_lop' => 'AB001', 'ten_lop' => 'Tên mới']))
            ->assertRedirect(route('lophoc.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('lop_hocs', ['id' => $lopHoc->id, 'ten_lop' => 'Tên mới']);
    }

    public function test_sua_khong_duoc_trung_ma_lop_khac(): void
    {
        LopHoc::factory()->create(['ma_lop' => 'AB001']);
        $lopHoc = LopHoc::factory()->create(['ma_lop' => 'AB002']);

        $this->put(route('lophoc.update', $lopHoc), $this->duLieuHopLe(['ma_lop' => 'AB001']))
            ->assertSessionHasErrors('ma_lop');
    }

    public function test_xoa_lop_hoc(): void
    {
        $lopHoc = LopHoc::factory()->create();

        $this->from(route('lophoc.index', ['trang_thai' => 1]))
            ->delete(route('lophoc.destroy', $lopHoc))
            ->assertRedirect(route('lophoc.index', ['trang_thai' => 1]));

        $this->assertDatabaseMissing('lop_hocs', ['id' => $lopHoc->id]);
    }

    public function test_xem_chi_tiet(): void
    {
        $lopHoc = LopHoc::factory()->create(['ten_lop' => 'Lớp chi tiết']);

        $this->get(route('lophoc.show', $lopHoc))->assertOk()->assertSee('Lớp chi tiết');
    }

    public function test_loc_theo_tu_khoa_trang_thai_va_si_so(): void
    {
        LopHoc::factory()->create(['ten_lop' => 'Toán cao cấp', 'ma_lop' => 'T001', 'giao_vien' => 'GV Một', 'si_so' => 20, 'trang_thai' => 1]);
        LopHoc::factory()->create(['ten_lop' => 'Vật lý', 'ma_lop' => 'V001', 'giao_vien' => 'GV Hai', 'si_so' => 35, 'trang_thai' => 0]);
        LopHoc::factory()->create(['ten_lop' => 'Hóa học', 'ma_lop' => 'H001', 'giao_vien' => 'GV Ba', 'si_so' => 50, 'trang_thai' => 1]);

        $this->get(route('lophoc.index', ['keyword' => 'Toán']))
            ->assertSee('Toán cao cấp')->assertDontSee('Vật lý')->assertDontSee('Hóa học');

        // Từ khóa tìm cả theo mã lớp và giáo viên
        $this->get(route('lophoc.index', ['keyword' => 'V001']))->assertSee('Vật lý')->assertDontSee('Hóa học');
        $this->get(route('lophoc.index', ['keyword' => 'GV Ba']))->assertSee('Hóa học')->assertDontSee('Vật lý');

        $this->get(route('lophoc.index', ['trang_thai' => 0]))
            ->assertSee('Vật lý')->assertDontSee('Toán cao cấp')->assertDontSee('Hóa học');

        $this->get(route('lophoc.index', ['si_so_min' => 30, 'si_so_max' => 40]))
            ->assertSee('Vật lý')->assertDontSee('Toán cao cấp')->assertDontSee('Hóa học');

        // Kết hợp nhiều điều kiện
        $this->get(route('lophoc.index', ['trang_thai' => 1, 'si_so_min' => 30]))
            ->assertSee('Hóa học')->assertDontSee('Toán cao cấp')->assertDontSee('Vật lý');
    }

    public function test_sap_xep(): void
    {
        LopHoc::factory()->create(['ten_lop' => 'Lop-B', 'si_so' => 10]);
        LopHoc::factory()->create(['ten_lop' => 'Lop-A', 'si_so' => 30]);
        LopHoc::factory()->create(['ten_lop' => 'Lop-C', 'si_so' => 20]);

        $this->get(route('lophoc.index', ['sort' => 'ten_lop', 'direction' => 'asc']))
            ->assertSeeInOrder(['Lop-A', 'Lop-B', 'Lop-C']);

        $this->get(route('lophoc.index', ['sort' => 'si_so', 'direction' => 'desc']))
            ->assertSeeInOrder(['Lop-A', 'Lop-C', 'Lop-B']);

        // Mặc định: theo ID tăng dần
        $this->get(route('lophoc.index'))->assertSeeInOrder(['Lop-B', 'Lop-A', 'Lop-C']);
    }

    public function test_tham_so_url_khong_hop_le_bi_bo_qua(): void
    {
        LopHoc::factory()->count(3)->create();

        $this->get('/lophoc?sort=password&direction=xyz&perPage=99999&trang_thai=abc&si_so_min=abc&keyword[]=x')
            ->assertOk()
            ->assertViewHas('sort', 'id')
            ->assertViewHas('direction', 'asc')
            ->assertViewHas('perPage', 10)
            ->assertViewHas('lopHocs', fn ($lopHocs) => $lopHocs->total() === 3);
    }

    public function test_phan_trang_giu_nguyen_bo_loc(): void
    {
        LopHoc::factory()->count(15)->create(['trang_thai' => 1]);

        $this->get(route('lophoc.index', ['trang_thai' => 1, 'sort' => 'si_so', 'perPage' => 10]))
            ->assertOk()
            ->assertSee('trang_thai=1', false)
            ->assertSee('sort=si_so', false)
            ->assertViewHas('lopHocs', fn ($lopHocs) => $lopHocs->count() === 10 && $lopHocs->total() === 15);
    }
}
