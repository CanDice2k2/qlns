<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Trang Chủ</h1>

    <!-- Thống kê tổng quan -->
    <div class="mb-8 bg-white rounded shadow overflow-hidden">
      <div class="p-6">
        <h2 class="text-lg font-semibold mb-4">Tổng quan hệ thống</h2>
        <div class="flex items-center">
          <div class="h-16 w-16 bg-indigo-500 rounded-full flex items-center justify-center mr-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div>
            <div class="text-sm text-gray-500">Tổng số nhân viên</div>
            <div class="text-2xl font-bold">{{ totalEmployees }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Biểu đồ -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Biểu đồ pie - Phân bổ nhân viên theo phòng ban -->
      <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-6">
          <h2 class="text-lg font-semibold mb-4">Phân bổ nhân viên theo phòng ban</h2>
          <div class="h-80">
            <canvas ref="departmentChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Biểu đồ cột chồng - Phân bổ nhân viên theo chức vụ và phòng ban -->
      <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-6">
          <h2 class="text-lg font-semibold mb-4">Phân bổ nhân viên theo chức vụ và phòng ban</h2>
          <div class="h-80">
            <canvas ref="positionChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Biểu đồ đường - Số lượng nhân viên chấm công theo ngày -->
    <div class="bg-white rounded shadow overflow-hidden">
      <div class="p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0 mb-6">
          <div>
            <h2 class="text-lg font-semibold">Số lượng nhân viên chấm công theo ngày</h2>
            <p class="text-gray-500 text-sm">Tổng số lượt chấm công: {{ totalAttendance }}</p>
          </div>
          <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
            <div>
              <label class="block text-sm text-gray-600">Từ ngày</label>
              <input
                type="date"
                v-model="filters.startDate"
                class="mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Đến ngày</label>
              <input
                type="date"
                v-model="filters.endDate"
                class="mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <button
              class="md:mt-6 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              @click="filterByDate"
              :disabled="isLoading"
            >
              {{ isLoading ? 'Đang tải...' : 'Áp dụng' }}
            </button>
          </div>
        </div>
        <div class="h-96">
          <canvas ref="attendanceChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import { Chart, registerables } from 'chart.js'
import { Inertia } from '@inertiajs/inertia'

// Đăng ký tất cả các components của Chart.js
Chart.register(...registerables)

export default {
  metaInfo: { title: 'Dashboard' },
  layout: Layout,
  props: {
    employeesByDepartment: Array,
    stackedChartData: Object,
    totalEmployees: Number,
    attendanceChartData: Object,
    dateRange: Object,
    totalAttendance: Number
  },
  watch: {
    // Theo dõi khi dữ liệu biểu đồ thay đổi
    attendanceChartData: {
      handler() {
        // Đảm bảo component đã được mount
        if (this.$refs.attendanceChart) {
          this.$nextTick(() => {
            // Xóa biểu đồ cũ nếu tồn tại
            if (this.attendanceChart) {
              this.attendanceChart.destroy();
            }
            // Tạo biểu đồ mới với dữ liệu mới
            this.createAttendanceChart();
          });
        }
      },
      deep: true
    }
  },
  data() {
    return {
      filters: {
        startDate: this.dateRange.startDate,
        endDate: this.dateRange.endDate
      },
      attendanceChart: null,
      isLoading: false
    }
  },
  mounted() {
    this.createDepartmentChart()
    this.createStackedPositionChart()
    this.createAttendanceChart()
  },
  methods: {
    createDepartmentChart() {
      const ctx = this.$refs.departmentChart.getContext('2d')

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: this.employeesByDepartment.map(item => item.name),
          datasets: [{
            data: this.employeesByDepartment.map(item => item.value),
            backgroundColor: [
              '#A5B4FC', '#6EE7B7', '#FDE68A', '#FCA5A5', '#C4B5FD',
              '#F9A8D4', '#93C5FD', '#A7BFFC', '#67E8F9', '#FDBA74'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.raw || 0;
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = Math.round((value / total) * 100);
                  return `${label}: ${value} (${percentage}%)`;
                }
              }
            }
          }
        }
      });
    },
    createStackedPositionChart() {
      const ctx = this.$refs.positionChart.getContext('2d')

      new Chart(ctx, {
        type: 'bar',
        data: this.stackedChartData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              stacked: true,
            },
            y: {
              stacked: true,
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          },
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                footer: (tooltipItems) => {
                  const sum = tooltipItems.reduce((a, b) => a + b.parsed.y, 0);
                  return `Tổng: ${sum} nhân viên`;
                }
              }
            }
          }
        }
      });
    },
    createAttendanceChart() {
      const ctx = this.$refs.attendanceChart.getContext('2d');

      // Xóa biểu đồ cũ nếu có
      if (this.attendanceChart) {
        this.attendanceChart.destroy();
      }

      // Tạo biểu đồ mới và lưu tham chiếu
      this.attendanceChart = new Chart(ctx, {
        type: 'line',
        data: this.attendanceChartData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              },
              title: {
                display: true,
                text: 'Số nhân viên'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Ngày'
              }
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                title: function(tooltipItems) {
                  const index = tooltipItems[0].dataIndex;
                  return `Ngày: ${this.attendanceChartData.labels[index]}`;
                }.bind(this),
                label: function(context) {
                  return `Số nhân viên: ${context.raw}`;
                }
              }
            },
            legend: {
              position: 'top',
            }
          }
        }
      });
    },
    filterByDate() {
      if (this.filters.startDate > this.filters.endDate) {
        alert('Ngày bắt đầu không thể sau ngày kết thúc');
        return;
      }

      this.isLoading = true;

      Inertia.get(route('dashboard'), {
        start_date: this.filters.startDate,
        end_date: this.filters.endDate
      }, {
        preserveState: false,
        replace: true,
        onSuccess: () => {
          this.isLoading = false;
        },
        onError: () => {
          this.isLoading = false;
          alert('Đã xảy ra lỗi khi tải dữ liệu');
        }
      });
    }
  }
}
</script>
