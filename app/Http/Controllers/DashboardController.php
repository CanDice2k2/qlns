<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\ChucVu;
use App\Models\PhuCap;
use App\Models\ChamCong;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy dữ liệu phân bổ nhân viên theo phòng ban (cho biểu đồ pie)
        $employeesByDepartment = NhanVien::join('phucap', 'nhanvien.phucap_id', '=', 'phucap.id')
            ->join('phongban', 'phucap.phongban_id', '=', 'phongban.id')
            ->whereNull('nhanvien.deleted_at')
            ->select('phongban.id', 'phongban.tenpb as ten_phong_ban', DB::raw('count(*) as total'))
            ->groupBy('phongban.id', 'phongban.tenpb')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->ten_phong_ban ?? 'Không xác định',
                    'value' => $item->total,
                ];
            });

        // Lấy dữ liệu chi tiết của nhân viên theo chức vụ và phòng ban
        $detailedData = NhanVien::join('phucap', 'nhanvien.phucap_id', '=', 'phucap.id')
            ->join('chucvu', 'phucap.chucvu_id', '=', 'chucvu.id')
            ->join('phongban', 'phucap.phongban_id', '=', 'phongban.id')
            ->whereNull('nhanvien.deleted_at')
            ->select(
                'chucvu.id as chucvu_id',
                'chucvu.tencv as ten_chuc_vu',
                'phongban.id as phongban_id',
                'phongban.tenpb as ten_phong_ban',
                DB::raw('count(*) as total')
            )
            ->groupBy('chucvu.id', 'chucvu.tencv', 'phongban.id', 'phongban.tenpb')
            ->get();

        // Chuẩn bị dữ liệu cho biểu đồ cột chồng
        $positions = [];
        $departments = [];

        // Thu thập danh sách chức vụ và phòng ban
        foreach ($detailedData as $item) {
            if (!in_array($item->ten_chuc_vu, $positions)) {
                $positions[] = $item->ten_chuc_vu;
            }
            if (!in_array($item->ten_phong_ban, $departments)) {
                $departments[] = $item->ten_phong_ban;
            }
        }

        // Tạo mảng dữ liệu cho mỗi phòng ban
        $departmentData = [];
        foreach ($departments as $dept) {
            $departmentData[$dept] = array_fill_keys($positions, 0);
        }

        // Điền dữ liệu vào mảng
        foreach ($detailedData as $item) {
            $departmentData[$item->ten_phong_ban][$item->ten_chuc_vu] = $item->total;
        }

        // Định dạng dữ liệu cho Chart.js
        $stackedChartData = [
            'labels' => $positions,
            'datasets' => []
        ];

        // Mảng màu sắc cho các phòng ban
        $colors = [
            '#A5B4FC', '#6EE7B7', '#FDE68A', '#FCA5A5', '#C4B5FD',
            '#F9A8D4', '#93C5FD', '#A7BFFC', '#67E8F9', '#FDBA74'
        ];

        // Tạo dataset cho mỗi phòng ban
        $colorIndex = 0;
        foreach ($departments as $dept) {
            $stackedChartData['datasets'][] = [
                'label' => $dept,
                'data' => array_values($departmentData[$dept]),
                'backgroundColor' => $colors[$colorIndex % count($colors)],
            ];
            $colorIndex++;
        }

        // Tổng số nhân viên
        $totalEmployees = NhanVien::whereNull('deleted_at')->count();

        // Lấy ngày bắt đầu và ngày kết thúc từ request, mặc định là 30 ngày gần nhất
        $startDate = Request::get('start_date', now()->subDays(29)->format('Y-m-d'));
        $endDate = Request::get('end_date', now()->format('Y-m-d'));

        // Lấy dữ liệu chấm công theo ngày
        $attendanceByDay = ChamCong::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tạo mảng các ngày từ ngày bắt đầu đến ngày kết thúc
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $dates = [];
        $counts = [];

        // Khởi tạo mảng với tất cả các ngày trong khoảng
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $formattedDate = $date->format('d/m');  // Định dạng ngắn gọn cho trục x
            $dates[] = $formattedDate;
            $counts[$dateString] = 0;
        }

        // Cập nhật số lượng nhân viên chấm công cho các ngày có dữ liệu
        foreach ($attendanceByDay as $record) {
            $counts[$record->date] = $record->count;
        }

        // Chuẩn bị dữ liệu cho biểu đồ
        $attendanceChartData = [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Số nhân viên chấm công',
                    'data' => array_values($counts),
                    'fill' => false,
                    'borderColor' => '#4F46E5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];

        // Tổng số nhân viên chấm công trong khoảng thời gian
        $totalAttendance = array_sum($counts);

        return Inertia::render('Dashboard/Index', [
            'employeesByDepartment' => $employeesByDepartment,
            'stackedChartData' => $stackedChartData,
            'totalEmployees' => $totalEmployees,
            'attendanceChartData' => $attendanceChartData,
            'dateRange' => [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'totalAttendance' => $totalAttendance,
        ]);
    }
}
