<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    private function duLieuHopLe(array $ghiDe = []): array
    {
        return array_merge([
            'ten' => 'Lớp học',
            'url' => '/lophoc',
            'vi_tri' => 'sidebar',
            'nhom' => 'Danh mục',
            'thu_tu' => 1,
            'trang_thai' => 1,
        ], $ghiDe);
    }

    public function test_cac_trang_form_hien_thi_duoc(): void
    {
        $menu = Menu::factory()->create(['ten' => 'Menu cần sửa']);

        $this->get(route('menu.create'))->assertOk()->assertSee('Thêm menu');
        $this->get(route('menu.edit', $menu))->assertOk()->assertSee('Menu cần sửa');
    }

    public function test_them_menu_hop_le(): void
    {
        $this->post(route('menu.store'), $this->duLieuHopLe())
            ->assertRedirect(route('menu.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('menus', ['ten' => 'Lớp học', 'url' => '/lophoc', 'vi_tri' => 'sidebar']);
    }

    public function test_form_rong_bao_loi_cac_truong_bat_buoc(): void
    {
        $this->post(route('menu.store'), [])
            ->assertSessionHasErrors(['ten', 'url', 'vi_tri', 'thu_tu', 'trang_thai'])
            ->assertSessionDoesntHaveErrors(['nhom']);

        $this->assertDatabaseCount('menus', 0);
    }

    public function test_duong_dan_va_vi_tri_khong_hop_le_bi_tu_choi(): void
    {
        $this->post(route('menu.store'), $this->duLieuHopLe(['url' => 'javascript:alert(1)', 'vi_tri' => 'footer', 'thu_tu' => -1]))
            ->assertSessionHasErrors([
                'url' => 'Đường dẫn phải bắt đầu bằng /, # hoặc http(s)://.',
                'vi_tri' => 'Vị trí không hợp lệ.',
                'thu_tu' => 'Thứ tự phải từ 0 trở lên.',
            ]);
    }

    public function test_chap_nhan_cac_dang_duong_dan_hop_le(): void
    {
        foreach (['/', '#', '/lophoc?trang_thai=1', 'https://huce.edu.vn'] as $url) {
            $this->post(route('menu.store'), $this->duLieuHopLe(['url' => $url]))->assertSessionHasNoErrors();
        }

        $this->assertDatabaseCount('menus', 4);
    }

    public function test_sua_va_xoa_menu(): void
    {
        $menu = Menu::factory()->create();

        $this->put(route('menu.update', $menu), $this->duLieuHopLe(['ten' => 'Tên mới']))
            ->assertRedirect(route('menu.index'))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'ten' => 'Tên mới']);

        $this->put(route('menu.update', $menu), $this->duLieuHopLe(['ten' => '']))->assertSessionHasErrors('ten');

        $this->delete(route('menu.destroy', $menu));
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_loc_danh_sach(): void
    {
        Menu::factory()->create(['ten' => 'Menu-Dau', 'url' => '/dau', 'vi_tri' => 'header', 'trang_thai' => 1]);
        Menu::factory()->create(['ten' => 'Menu-Giua', 'url' => '/giua', 'vi_tri' => 'sidebar', 'trang_thai' => 0]);
        Menu::factory()->create(['ten' => 'Menu-Cuoi', 'url' => '/cuoi', 'vi_tri' => 'sidebar', 'trang_thai' => 1]);

        $idTimThay = fn (array $query) => $this->get(route('menu.index', $query))
            ->assertOk()
            ->viewData('menus')->pluck('ten')->all();

        $this->assertSame(['Menu-Dau'], $idTimThay(['keyword' => 'Dau']));
        $this->assertSame(['Menu-Giua'], $idTimThay(['keyword' => '/giua']));
        $this->assertSame(['Menu-Dau'], $idTimThay(['vi_tri' => 'header']));
        $this->assertSame(['Menu-Giua'], $idTimThay(['trang_thai' => 0]));
        $this->assertSame(['Menu-Cuoi'], $idTimThay(['vi_tri' => 'sidebar', 'trang_thai' => 1]));
    }

    public function test_sap_xep_danh_sach(): void
    {
        Menu::factory()->create(['ten' => 'B', 'vi_tri' => 'sidebar', 'thu_tu' => 2]);
        Menu::factory()->create(['ten' => 'A', 'vi_tri' => 'sidebar', 'thu_tu' => 1]);
        Menu::factory()->create(['ten' => 'C', 'vi_tri' => 'header', 'thu_tu' => 3]);

        $thuTu = fn (array $query) => $this->get(route('menu.index', $query))->viewData('menus')->pluck('ten')->all();

        // Mặc định: theo vị trí, rồi theo thứ tự hiển thị
        $this->assertSame(['C', 'A', 'B'], $thuTu([]));
        $this->assertSame(['C', 'B', 'A'], $thuTu(['sort' => 'ten', 'direction' => 'desc']));
        $this->assertSame(['A', 'B', 'C'], $thuTu(['sort' => 'thu_tu']));
        // Tham số sai thì dùng mặc định
        $this->assertSame(['C', 'A', 'B'], $thuTu(['sort' => 'password', 'direction' => 'xyz']));
    }

    public function test_layout_hien_menu_tu_csdl_theo_thu_tu_va_trang_thai(): void
    {
        Menu::factory()->create(['ten' => 'SB-Hai', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Nhóm thử', 'thu_tu' => 2]);
        Menu::factory()->create(['ten' => 'SB-Mot', 'url' => '/lophoc', 'vi_tri' => 'sidebar', 'nhom' => 'Nhóm thử', 'thu_tu' => 1]);
        Menu::factory()->create(['ten' => 'SB-An', 'vi_tri' => 'sidebar', 'trang_thai' => 0]);
        Menu::factory()->create(['ten' => 'HD-Mot', 'vi_tri' => 'header', 'thu_tu' => 1]);

        $this->get(route('lophoc.create'))
            ->assertOk()
            ->assertSeeInOrder(['HD-Mot', 'Nhóm thử', 'SB-Mot', 'SB-Hai'])
            ->assertDontSee('SB-An')
            // Sidebar không còn mục viết cứng nào
            ->assertDontSee('Quản lý menu')
            // Menu trỏ tới /lophoc được tô sáng khi đang ở /lophoc/create
            ->assertSee('<a href="/lophoc" class="active">SB-Mot</a>', false);
    }

    public function test_layout_van_chay_khi_chua_co_menu_nao(): void
    {
        $this->get(route('lophoc.index'))->assertOk()->assertDontSee('Quản trị');
        $this->get('/sinhvien')->assertOk();
    }

    public function test_seeder_nap_menu_quan_tri_vao_sidebar(): void
    {
        $this->seed(\Database\Seeders\MenuSeeder::class);

        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSeeInOrder(['Quản trị', 'Quản lý lớp học'])
            ->assertSee('<a href="/menu" class="active">Quản lý menu</a>', false);
    }
}
