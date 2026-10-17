<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SinhVien;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private const SORTABLE = ['id', 'name', 'email', 'role', 'created_at'];

    private const ROLE_OPTIONS = [
        'admin' => 'Quản trị viên',
        'giang_vien' => 'Giảng viên',
        'sinh_vien' => 'Sinh viên',
    ];

    private const PER_PAGE_OPTIONS = [10, 20, 50, 100];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();

        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'role' => 'nullable|in:admin,giang_vien,sinh_vien',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|in:' . implode(',', self::PER_PAGE_OPTIONS),
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        $perPage = (int) ($filters['perPage'] ?? 10);

        $usersQuery = User::query()
            ->with(['sinhVien', 'giangVien'])
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $keyword = '%' . $filters['keyword'] . '%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword);
                });
            })
            ->when(isset($filters['role']), fn ($query) => $query->where('role', $filters['role']))
            ->orderBy($sort, $direction);

        // Giảng viên chỉ xem được tài khoản của mình
        if ($currentUser->role === 'giang_vien') {
            $usersQuery->where('id', $currentUser->id);
        }

        $users = $usersQuery->paginate($perPage)->withQueryString();

        return view('users.index', [
            'users' => $users,
            'filters' => $filters,
            'sort' => $sort,
            'direction' => $direction,
            'perPage' => $perPage,
            'sortable' => self::SORTABLE,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
            'roleOptions' => self::ROLE_OPTIONS,
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sinhViens = SinhVien::whereDoesntHave('user')->get();
        $giangViens = GiangVien::whereDoesntHave('user')->get();

        return view('users.create', [
            'roleOptions' => self::ROLE_OPTIONS,
            'sinhViens' => $sinhViens,
            'giangViens' => $giangViens,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,giang_vien,sinh_vien',
            'sinh_vien_id' => 'nullable|exists:sinh_viens,id',
            'giang_vien_id' => 'nullable|exists:giang_viens,id',
        ], [
            'required' => ':attribute không được để trống.',
            'email' => ':attribute không hợp lệ.',
            'unique' => ':attribute đã tồn tại.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
            'in' => ':attribute không hợp lệ.',
            'exists' => ':attribute không tồn tại.',
        ], [
            'name' => 'Tên',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'role' => 'Vai trò',
            'sinh_vien_id' => 'Sinh viên',
            'giang_vien_id' => 'Giảng viên',
        ]);

        // Validate logic: role phải tương ứng với ID
        if ($validated['role'] === 'sinh_vien' && empty($validated['sinh_vien_id'])) {
            return back()->withInput()->withErrors(['sinh_vien_id' => 'Vui lòng chọn sinh viên cho vai trò sinh viên.']);
        }

        if ($validated['role'] === 'giang_vien' && empty($validated['giang_vien_id'])) {
            return back()->withInput()->withErrors(['giang_vien_id' => 'Vui lòng chọn giảng viên cho vai trò giảng viên.']);
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'sinh_vien_id' => $validated['role'] === 'sinh_vien' ? $validated['sinh_vien_id'] : null,
            'giang_vien_id' => $validated['role'] === 'giang_vien' ? $validated['giang_vien_id'] : null,
        ]);

        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $currentUser = request()->user();

        // Giảng viên chỉ xem được tài khoản của mình
        if ($currentUser->role === 'giang_vien' && $currentUser->id !== $user->id) {
            abort(403, 'Bạn không có quyền xem tài khoản này.');
        }

        return view('users.show', ['user' => $user->load(['sinhVien', 'giangVien'])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $currentUser = request()->user();

        // Giảng viên chỉ sửa được tài khoản của mình
        if ($currentUser->role === 'giang_vien' && $currentUser->id !== $user->id) {
            abort(403, 'Bạn không có quyền sửa tài khoản này.');
        }

        $sinhViens = SinhVien::whereDoesntHave('user')
            ->orWhere('id', $user->sinh_vien_id)
            ->get();

        $giangViens = GiangVien::whereDoesntHave('user')
            ->orWhere('id', $user->giang_vien_id)
            ->get();

        return view('users.edit', [
            'user' => $user,
            'roleOptions' => self::ROLE_OPTIONS,
            'sinhViens' => $sinhViens,
            'giangViens' => $giangViens,
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = request()->user();

        // Giảng viên chỉ sửa được tài khoản của mình
        if ($currentUser->role === 'giang_vien' && $currentUser->id !== $user->id) {
            abort(403, 'Bạn không có quyền sửa tài khoản này.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,giang_vien,sinh_vien',
            'sinh_vien_id' => 'nullable|exists:sinh_viens,id',
            'giang_vien_id' => 'nullable|exists:giang_viens,id',
        ], [
            'required' => ':attribute không được để trống.',
            'email' => ':attribute không hợp lệ.',
            'unique' => ':attribute đã tồn tại.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
            'in' => ':attribute không hợp lệ.',
            'exists' => ':attribute không tồn tại.',
        ], [
            'name' => 'Tên',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'role' => 'Vai trò',
            'sinh_vien_id' => 'Sinh viên',
            'giang_vien_id' => 'Giảng viên',
        ]);

        // Validate logic: role phải tương ứng với ID
        if ($validated['role'] === 'sinh_vien' && empty($validated['sinh_vien_id'])) {
            return back()->withInput()->withErrors(['sinh_vien_id' => 'Vui lòng chọn sinh viên cho vai trò sinh viên.']);
        }

        if ($validated['role'] === 'giang_vien' && empty($validated['giang_vien_id'])) {
            return back()->withInput()->withErrors(['giang_vien_id' => 'Vui lòng chọn giảng viên cho vai trò giảng viên.']);
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'sinh_vien_id' => $validated['role'] === 'sinh_vien' ? $validated['sinh_vien_id'] : null,
            'giang_vien_id' => $validated['role'] === 'giang_vien' ? $validated['giang_vien_id'] : null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'Người dùng đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $currentUser = request()->user();

        // Giảng viên không được xóa tài khoản
        if ($currentUser->role === 'giang_vien') {
            abort(403, 'Bạn không có quyền xóa tài khoản.');
        }

        // Không cho xóa chính mình
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        $user->delete();

        return back()->with('success', 'Người dùng đã được xóa.');
    }
}
