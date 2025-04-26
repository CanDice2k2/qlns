<template>
  <div>
    <!-- Thêm title và các nút chọn -->
    <div class="mb-8 flex justify-between items-center">
      <h1 class="font-bold text-3xl">Bảng Chấm Công Tháng {{ currentMonth }}/{{ currentYear }}</h1>

      <div class="flex space-x-4">
        <select v-model="selectedMonth" class="border rounded px-3 py-1">
          <option v-for="(name, index) in monthNames" :key="index" :value="index">
            {{ name }}
          </option>
        </select>
        <select v-model="selectedYear" class="border rounded px-3 py-1">
          <option v-for="year in yearRange" :key="year" :value="year">
            {{ year }}
          </option>
        </select>
        <button @click="changeMonth" class="btn-indigo">Xem</button>
      </div>
    </div>

    <!-- Thêm search filter nhân viên -->
    <div class="mb-6">
      <search-filter v-model="form.search" class="w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Tìm kiếm nhân viên:</label>
        <input v-model="form.search" class="mt-1 w-full form-input" type="text" placeholder="Tên nhân viên..."/>
      </search-filter>
    </div>

    <!-- Bảng hiển thị chấm công -->
    <div class="bg-white shadow overflow-x-auto">
      <div class="p-1 text-center bg-yellow-300 font-bold text-xl">
        BẢNG CHẤM CÔNG THÁNG {{ currentMonth }}/{{ currentYear }}
      </div>
      <table class="w-full">
        <thead>
          <tr class="bg-gray-100 text-center">
            <th rowspan="2" class="border px-4 py-2">TT</th>
            <th rowspan="2" class="border px-4 py-2">Họ và tên</th>
            <th rowspan="2" class="border px-4 py-2">Chức vụ/Bộ phận</th>
            <th :colspan="days.length" class="border px-4 py-2">Ngày trong tháng</th>
            <th rowspan="2" class="border px-4 py-2">Tổng<br>công<br>ngày</th>
          </tr>
          <tr class="bg-gray-50 text-center text-xs">
            <th
              v-for="(day, index) in days"
              :key="index"
              class="border w-10"
              :class="{'bg-red-100': day.isSunday}"
            >
              <div>{{ day.day }}</div>
              <div>{{ day.label }}</div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(nv, index) in filteredNhanVien" :key="nv.id" class="hover:bg-gray-50">
            <td class="border px-2 py-1 text-center">{{ index + 1 }}</td>
            <td class="border px-2 py-1">{{ nv.hovaten }}</td>
            <td class="border px-2 py-1">{{ nv.chucvu }}</td>

            <td
              v-for="day in days"
              :key="`${nv.id}-${day.day}`"
              class="border text-center cursor-pointer"
              :class="{
                'bg-red-50': day.isSunday,
                'bg-blue-100': isAttendanceMarked(nv, day.day)
              }"
              @click="toggleAttendance(nv.id, day.day)"
            >
              <div class="h-8 flex items-center justify-center">
                <template v-if="isAttendanceMarked(nv, day.day)">
                  <template v-if="day.isSunday">x2</template>
                  <template v-else>x</template>
                </template>
                <template v-else>&nbsp;</template>
              </div>
            </td>

            <td class="border px-4 py-2 text-center font-medium">{{ nv.tong_cong }}</td>
          </tr>

          <!-- Thêm thông báo khi không tìm thấy nhân viên nào -->
          <tr v-if="filteredNhanVien.length === 0">
            <td class="border-t px-6 py-4 text-center" :colspan="days.length + 4">Không tìm thấy nhân viên nào</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-4 bg-gray-50 p-4 rounded">
      <h3 class="font-semibold mb-2">Chú thích:</h3>
      <div class="flex space-x-8">
        <div class="flex items-center">
          <div class="w-5 h-5 bg-blue-100 border mr-2"></div>
          <span>Ngày công bình thường</span>
        </div>
        <div class="flex items-center">
          <div class="w-5 h-5 bg-red-50 border mr-2"></div>
          <span>Chủ nhật (tính x2 công)</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import axios from 'axios'
import SearchFilter from '@/Shared/SearchFilter'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'

export default {
  metaInfo: { title: 'Bảng Chấm Công Tháng' },
  layout: Layout,
  components: {
    SearchFilter
  },
  props: {
    nhanVien: Array,
    days: Array,
    month: String,
    year: String,
    monthNames: Object,
    currentMonth: Number,
    currentYear: Number,
    filters: Object
  },
  data() {
    return {
      selectedMonth: this.currentMonth,
      selectedYear: this.currentYear,
      loading: false,
      form: {
        search: this.filters?.search || ''
      }
    }
  },
  computed: {
    yearRange() {
      const currentYear = new Date().getFullYear();
      return Array.from({length: 5}, (_, i) => currentYear - 2 + i);
    },
    filteredNhanVien() {
      if (!this.form.search) {
        return this.nhanVien;
      }

      const searchTerm = this.form.search.toLowerCase();
      return this.nhanVien.filter(nv =>
        nv.hovaten.toLowerCase().includes(searchTerm) ||
        nv.chucvu.toLowerCase().includes(searchTerm)
      );
    }
  },
  watch: {
    form: {
      handler: throttle(function() {
        // Cập nhật URL với search param
        let query = pickBy(this.form);
        // Gộp với các params hiện tại: month, year
        query.month = this.selectedMonth;
        query.year = this.selectedYear;

        this.$inertia.replace(this.route('chamcong.monthly', query));
      }, 150),
      deep: true,
    },
  },
  created() {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => '');
    },
    isAttendanceMarked(nhanvien, day) {
      // Kiểm tra cả 3 dạng: số nguyên, chuỗi không có số 0, và chuỗi có số 0 đằng trước
      const dayAsInt = parseInt(day, 10);
      const dayAsString = String(day);
      const dayWithLeadingZero = String(day).padStart(2, '0');

      return nhanvien.ngay_cong[dayAsInt] !== undefined ||
             nhanvien.ngay_cong[dayAsString] !== undefined ||
             nhanvien.ngay_cong[dayWithLeadingZero] !== undefined;
    },

    async toggleAttendance(nhanvienId, day) {
      if (this.loading) return;
      this.loading = true;

      try {
        const date = `${this.year}-${String(this.month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        await axios.post('/chamcong/toggle', {
            nhanvien_id: nhanvienId,
            date: date
        }, {
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        this.$inertia.reload();
      } catch (error) {
        console.error('Error toggling attendance:', error);
        console.error('Response:', error.response ? error.response.data : 'No response data');
        alert('Có lỗi xảy ra khi cập nhật chấm công. Kiểm tra console để biết thêm chi tiết.');
      } finally {
        this.loading = false;
      }
    },

    changeMonth() {
      // Khi thay đổi tháng/năm, giữ lại tham số tìm kiếm
      const params = {
        month: this.selectedMonth,
        year: this.selectedYear
      };

      if (this.form.search) {
        params.search = this.form.search;
      }

      this.$inertia.visit(route('chamcong.monthly', params));
    }
  }
}
</script>
