<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Nhân Viên</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
        <label class="block text-gray-700">Trạng thái xoá:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option :value="null">- Chưa chọn -</option>
          <option value="only">Đã xoá</option>
          <option value="with">Tất cả</option>
        </select>
        <label class="mt-4 block text-gray-700">Giới tính:</label>
        <select v-model="form.gioitinh" class="mt-1 w-full form-select">
          <option :value="null">- Chưa chọn -</option>
          <option value="nam">Nam</option>
          <option value="nu">Nữ</option>
        </select>
        <label class="mt-4 block text-gray-700">Trạng thái làm việc:</label>
        <select v-model="form.trangthai" class="mt-1 w-full form-select">
          <option :value="null">- Chưa chọn -</option>
          <option value="danghilam">Đã nghĩ làm</option>
          <option value="danglamviec">Đang làm việc</option>
        </select>
      </search-filter>
      <div>
        <a href="javascript:void(0)" class="btn-indigo" @click="openModal"><span>Import</span></a>
        <a :href="route('nhanvien.export')" class="btn-indigo" target="_blank"><span>Export</span></a>
        <inertia-link class="btn-indigo" :href="route('nhanvien.create')">
          <span>Tạo Mới</span>
        </inertia-link>
      </div>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Mã nhân viên</th>
          <th class="px-6 pt-6 pb-4">Họ và tên</th>
          <th class="px-6 pt-6 pb-4">Email</th>
          <th class="px-6 pt-6 pb-4">Số điện thoại</th>
          <th class="px-6 pt-6 pb-4">Giới tính</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Trạng thái</th>
        </tr>
        <tr v-for="nv in nhanvien.data" :key="nv.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('nhanvien.edit', nv.id)" tabindex="-1">
              <img v-if="nv.photo" class="block w-5 h-5 rounded-full mr-2 -my-2" :src="nv.photo" />
              {{ nv.manv }}
              <icon v-if="nv.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2" />
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('nhanvien.edit', nv.id)">
              {{ nv.hovaten }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('nhanvien.edit', nv.id)" tabindex="-1">
              {{ nv.email }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('nhanvien.edit', nv.id)" tabindex="-1">
              {{ nv.sdt }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('nhanvien.edit', nv.id)" tabindex="-1">
              {{ nv.gioitinh ? 'Nữ' : 'Nam'}}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('nhanvien.edit', nv.id)" tabindex="-1">
              {{ nv.trangthai ? 'Đang làm việc' : 'Đã nghĩ làm' }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('nhanvien.edit', nv.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="nhanvien.data.length === 0">
          <td class="border-t px-6 py-4" colspan="5">Không có nhân viên nào cả.</td>
        </tr>
      </table>
    </div>
    <pagination :links="nhanvien.links" />
    <div class="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster" style="background: rgba(0, 0, 0, 0.7); display: none;">
      <div class="border border-teal-500 shadow-lg modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <div class="modal-content py-4 text-left px-6">
          <form @submit.prevent="store">
            <!--Title-->
            <div class="flex justify-between items-center pb-3">
              <p class="text-2xl font-bold">Import</p>
              <div class="modal-close cursor-pointer z-50" @click="closeModal">
                <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                  <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                </svg>
              </div>
            </div>
            <!--Body-->
            <div class="my-5 leading-6">
              <file-input v-model="form.fileimport" class="w-full lg:w-1/1" type="file" label="Chọn File Excel" />
            </div>
            <!--Footer-->
            <div class="flex justify-end pt-2">
              <button class="focus:outline-none modal-close px-4 bg-gray-400 p-3 rounded-lg text-white hover:bg-gray-300" @click="closeModal">Thoát</button>
              <button type="submit" class="focus:outline-none px-4 btn-indigo p-3 ml-3 rounded-lg text-white hover:bg-teal-400">Tải Lên</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import pickBy from 'lodash/pickBy'
import SearchFilter from '@/Shared/SearchFilter'
import throttle from 'lodash/throttle'
import FileInput from '@/Shared/FileInput'
export default {
  metaInfo: { title: 'Nhân Viên' },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter,
    FileInput,
  },
  props: {
    nhanvien: Object,
    filters: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        gioitinh: this.filters.gioitinh,
        trangthai: this.filters.trangthai,
        fileimport: null,
      },
      modal: null,
    }
  },
  watch: {
    form: {
      handler: throttle(function() {
        let query = pickBy(this.form)
        this.$inertia.replace(this.route('nhanvien', Object.keys(query).length ? query : { remember: 'forget' }))
      }, 150),
      deep: true,
    },
  },
  mounted() {
    this.modal = document.querySelector('.main-modal');
  },
  methods: {
    store() {
      console.log(this.form.fileimport);
      var formData = new FormData();
      formData.append('file_import', this.form.fileimport);
      axios.post('/nhanvien/import', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
      }).then(() => {
        alert('Đã upload file thành công');
        window.location.reload();
      })
      .catch(() => {
        alert('Đã xảy ra lỗi! Vui lòng thử lại.');
        window.location.reload();
      });
    },
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    openModal() {
      if (this.modal)
      {
        this.modal.classList.remove('fadeOut');
        this.modal.classList.add('fadeIn');
        this.modal.style.display = 'flex';
      }
    },
    closeModal() {
      if (this.modal)
      {
        this.modal.classList.remove('fadeIn');
        this.modal.classList.add('fadeOut');
        setTimeout(() => this.modal.style.display = 'none', 500);
      }
    }
  },
}
</script>
