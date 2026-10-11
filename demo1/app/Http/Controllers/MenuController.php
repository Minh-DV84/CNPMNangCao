<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    // Các cột được phép sắp xếp (không lấy thẳng tên cột từ URL để tránh SQL injection)
    private const SORTABLE = ['id', 'ten', 'url', 'vi_tri', 'nhom', 'thu_tu', 'trang_thai'];

    private const PER_PAGE_OPTIONS = [10, 20, 50, 100];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // valid() chỉ giữ lại những tham số hợp lệ, cái nào sai thì bỏ qua.
        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'vi_tri' => 'nullable|in:' . implode(',', array_keys(Menu::VI_TRI)),
            'trang_thai' => 'nullable|in:0,1',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|in:' . implode(',', self::PER_PAGE_OPTIONS),
        ])->valid();

        $sort = $filters['sort'] ?? 'vi_tri';
        $direction = $filters['direction'] ?? 'asc';
        $perPage = (int) ($filters['perPage'] ?? 10);

        $menus = Menu::query()
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $keyword = '%' . $filters['keyword'] . '%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('ten', 'like', $keyword)
                        ->orWhere('url', 'like', $keyword)
                        ->orWhere('nhom', 'like', $keyword);
                });
            })
            ->when(isset($filters['vi_tri']), fn ($query) => $query->where('vi_tri', $filters['vi_tri']))
            ->when(isset($filters['trang_thai']), fn ($query) => $query->where('trang_thai', $filters['trang_thai']))
            ->orderBy($sort, $direction)
            // Các dòng bằng nhau ở cột đang sắp xếp thì xếp tiếp theo thứ tự hiển thị
            ->orderBy('thu_tu')
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('menus.index', [
            'menus' => $menus,
            'filters' => $filters,
            'sort' => $sort,
            'direction' => $direction,
            'perPage' => $perPage,
            'sortable' => self::SORTABLE,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        Menu::create($request->validated());

        return redirect()->route('menu.index')->with('success', 'Menu đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return view('menus.edit', ['menu' => $menu]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        return redirect()->route('menu.index')->with('success', 'Menu đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        // back() để giữ nguyên bộ lọc/sắp xếp đang xem
        return back()->with('success', 'Menu đã được xóa.');
    }
}
