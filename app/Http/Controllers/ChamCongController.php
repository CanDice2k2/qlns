<?php

namespace App\Http\Controllers;

use App\Models\ChamCong;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ChamCongController extends Controller
{
    public function index()
    {
        if (empty(Request::get('nhanvien')) && Auth::user()->nhanvien->id !== Request::get('nhanvien'))
            return abort(404);

        return Inertia::render('ChamCong/Index', [
            'filters' => Request::all('search', 'trashed', 'nhanvien'),
            'chamcong' => (new ChamCong())
                ->latest('chamcong.created_at')
                ->filter(Request::only('search', 'trashed', 'nhanvien'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($chamcong) => [
                    'id' => $chamcong->id,
                    'hovaten' => $chamcong->nhanvien->hovaten,
                    'created_at' => date('d-m-Y', strtotime($chamcong->created_at)),
                    'deleted_at' => $chamcong->deleted_at,
                ]),
        ]);
    }

    public function create(NhanVien $nhanvien)
    {
        return Inertia::render('ChamCong/Create', [
            'nhanvien' => [
                'id' => $nhanvien->id,
                'hovaten' => $nhanvien->hovaten
            ]
        ]);
    }

    public function store(NhanVien $nhanvien)
    {
        Request::validate([
            'created_at' => ['required', 'date', Rule::unique('chamcong')->where('nhanvien_id', $nhanvien->id)]
        ]);

        (new ChamCong())->create([
            'nhanvien_id' => $nhanvien->id,
            'created_at' => Request::get('created_at')
        ]);

        return Redirect::route('chamcong', ['nhanvien' => $nhanvien->id])->with('success', 'Đã tạo thành công.');
    }

    public function edit(ChamCong $chamcong)
    {
        return Inertia::render('ChamCong/Edit', [
            'nhanvien' => [
                'id' => $chamcong->nhanvien->id,
                'hovaten' => $chamcong->nhanvien->hovaten
            ],
            'chamcong' => [
                'id' => $chamcong->id,
                'created_at' => date('Y-m-d', strtotime($chamcong->created_at)),
                'deleted_at' => $chamcong->deleted_at,
            ],
        ]);
    }

    public function update(ChamCong $chamcong)
    {
        Request::validate([
            'created_at' => ['required', 'date', Rule::unique('chamcong')->where('nhanvien_id', $chamcong->nhanvien->id)->ignore($chamcong->id)]
        ]);

        $chamcong->update(Request::only('created_at'));

        return Redirect::back()->with('success', 'Đã cập nhật thành công.');
    }

    public function destroy(ChamCong $chamcong)
    {
        $id = $chamcong->nhanvien->id;
        $chamcong->forceDelete();

        return Redirect::route('chamcong', ['nhanvien' => $id])->with('success', 'Đã xoá thành công.');
    }

    public function restore(ChamCong $chamcong)
    {
        $chamcong->restore();

        return Redirect::back()->with('success', 'Đã khôi phục thành công.');
    }

    public function monthly($month = null, $year = null)
    {
        // Nếu không chọn tháng/năm, sử dụng tháng hiện tại
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        // Lấy tham số tìm kiếm
        $search = Request::input('search');

        // Tạo ngày đầu tháng và cuối tháng
        $startDate = $year . '-' . $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        // Tính số ngày trong tháng
        $daysInMonth = date('t', strtotime($startDate));

        // Query builder để lấy nhân viên, thêm điều kiện tìm kiếm nếu có
        $nhanvienQuery = NhanVien::where('trangthai', true);

        if ($search) {
            $nhanvienQuery->where('hovaten', 'LIKE', '%'.$search.'%');
        }

        // Lấy tất cả nhân viên đang làm việc
        $nhanvien = $nhanvienQuery
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($nv) use ($month, $year, $startDate, $endDate) {
                // Lấy các ngày công trong tháng của nhân viên
                $ngayCong = ChamCong::where('nhanvien_id', $nv->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->get()
                    ->map(function ($chamcong) {
                        // Lấy ngày không có số 0 ở đầu để khớp với format hiển thị
                        return [
                            'id' => $chamcong->id,
                            'date' => (int)date('d', strtotime($chamcong->created_at)), // Chuyển thành số nguyên
                            'x2' => date('N', strtotime($chamcong->created_at)) == 7, // Ngày chủ nhật (N=7)
                        ];
                    })
                    ->keyBy('date');

                return [
                    'id' => $nv->id,
                    'hovaten' => $nv->hovaten,
                    'chucvu' => $nv->phucap ? ($nv->phucap->chucVu ? $nv->phucap->chucVu->tencv : 'N/A') : 'N/A',
                    'phongban' => $nv->phucap ? ($nv->phucap->phongBan ? $nv->phucap->phongBan->tenpb : 'N/A') : 'N/A',
                    'ngay_cong' => $ngayCong,
                    'tong_cong' => $ngayCong->sum(function ($item) {
                        return isset($item['x2']) && $item['x2'] ? 2 : 1;
                    }),
                ];
            });

        // Tạo mảng chứa thông tin các ngày trong tháng
        $days = [];
        for ($i = 1; $i <= $daysInMonth; $i++) { // Chỉ tạo ngày cho số ngày thực tế của tháng
            $dateString = $year . '-' . $month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('N', strtotime($dateString)); // 1-7 (Monday-Sunday)
            $days[] = [
                'day' => $i,
                'dayOfWeek' => $dayOfWeek,
                'isSunday' => $dayOfWeek == 7,
                'label' => $this->getDayLabel($dayOfWeek),
            ];
        }

        return Inertia::render('ChamCong/Monthly', [
            'nhanVien' => $nhanvien,
            'days' => $days,
            'month' => $month,
            'year' => $year,
            'monthNames' => $this->getMonthNames(),
            'currentMonth' => intval($month),
            'currentYear' => intval($year),
            'daysInMonth' => $daysInMonth,
            'filters' => Request::only('search') // Thêm filters để giữ giá trị tìm kiếm
        ]);
    }

    // Helper để lấy tên thứ trong tuần
    private function getDayLabel($day)
    {
        $days = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
        return $days[$day - 1];
    }

    // Helper để lấy tên các tháng
    private function getMonthNames()
    {
        return [
            1 => 'Tháng 1',
            2 => 'Tháng 2',
            3 => 'Tháng 3',
            4 => 'Tháng 4',
            5 => 'Tháng 5',
            6 => 'Tháng 6',
            7 => 'Tháng 7',
            8 => 'Tháng 8',
            9 => 'Tháng 9',
            10 => 'Tháng 10',
            11 => 'Tháng 11',
            12 => 'Tháng 12'
        ];
    }

    // API để đánh dấu chấm công
    public function toggleAttendance(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nhanvien_id' => 'required|exists:nhanvien,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $nhanvienId = $request->input('nhanvien_id');
        $date = $request->input('date');

        // Kiểm tra xem đã có bản ghi chấm công chưa
        $chamCong = ChamCong::where('nhanvien_id', $nhanvienId)
                    ->whereDate('created_at', $date)
                    ->first();

        if ($chamCong) {
            // Nếu đã có, xóa bản ghi đó
            $chamCong->delete();
            return response()->json(['status' => 'removed']);
        } else {
            // Nếu chưa có, tạo bản ghi mới
            ChamCong::create([
                'nhanvien_id' => $nhanvienId,
                'created_at' => $date . ' 00:00:00'
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
