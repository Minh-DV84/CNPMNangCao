<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    //
    private $sinhviens = [
        ['id' => 1, 'name' => 'Nguyen Van A', 'age' => 20],
        ['id' => 2, 'name' => 'Tran Thi B', 'age' => 21],
        ['id' => 3, 'name' => 'Le Van C', 'age' => 22],
        ['id' => 4, 'name' => 'Pham Thi D', 'age' => 23],
        ['id' => 5, 'name' => 'Hoang Van E', 'age' => 24],
    ];

    public function index()
    {
        return view('sinhvien.index',[
            'title' => 'Danh sách sinh viên',
            'sinhviens' => $this->sinhviens
        ]);
    }
    public function show($hoten="Chưa có tên", $tuoi=0)
    {
        return "Đây là sinh viên có tên = $hoten và tuổi = $tuoi";
    }

    public function add()
    {
        return view('sinhvien.add',[
            'title' => 'Thêm sinh viên'
        ]);
    }   
    public function store(Request $request)
    {
        dd($request->all());
    }
    public function edit($id)
    {
       
    }
    public function update(Request $request, $id)
    {
       
    }
    public function delete($id)
    {
       
    }   
}
