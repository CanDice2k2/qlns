<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien')">Nhân Viên</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Thêm Mới
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.hovaten" :error="form.errors.hovaten" class="pr-6 pb-8 w-full lg:w-1/2" label="Họ và tên" />
          <select-input v-model="form.gioitinh" :error="form.errors.gioitinh" class="pr-6 pb-8 w-full lg:w-1/2" label="Giới tính">
            <option :value="null">- Chọn -</option>
            <option :value="0">Nam</option>
            <option :value="1">Nữ</option>
          </select-input>
          <select-input v-model="form.trangthai" :error="form.errors.trangthai" class="pr-6 pb-8 w-full lg:w-1/2" label="Trạng thái làm việc">
            <option :value="null">- Chọn -</option>
            <option :value="0">Đã nghĩ việc</option>
            <option :value="1">Đang làm việc</option>
          </select-input>
          <text-input v-model="form.ngaysinh" :error="form.errors.ngaysinh" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày sinh" />
          <text-input v-model="form.hesoluong" :error="form.errors.hesoluong" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số lương" />
          <select-input v-model="form.phucap" :error="form.errors.phucap" class="pr-6 pb-8 w-full lg:w-1/2" label="Phòng ban -> chức vụ">
            <option :value="null">- Chọn -</option>
            <option v-for="pc in phucap" :key="pc.id" :value="pc.id">{{ pc.tenpb }} -> {{ pc.tencv }}</option>
          </select-input>
          <select-input v-model="form.bacluong" :error="form.errors.bacluong" class="pr-6 pb-8 w-full lg:w-1/2" label="Bậc lương">
            <option :value="null">- Chọn -</option>
            <option :value="1">Bậc 1</option>
            <option :value="2">Bậc 2</option>
            <option :value="3">Bậc 3</option>
            <option :value="4">Bậc 4</option>
            <option :value="5">Bậc 5</option>
            <option :value="6">Bậc 6</option>
            <option :value="7">Bậc 7</option>
            <option :value="8">Bậc 8</option>
            <option :value="9">Bậc 9</option>
            <option :value="10">Bậc 10</option>
          </select-input>
          <select-input v-model="form.bangcap" :error="form.errors.bangcap" class="pr-6 pb-8 w-full lg:w-1/2" label="Bằng cấp">
            <option :value="null">- Chọn -</option>
            <option v-for="bc in bangcap" :key="bc.id" :value="bc.id">{{ bc.tenbc }}</option>
          </select-input>
          <select-input v-model="form.ngoaingu" :error="form.errors.ngoaingu" class="pr-6 pb-8 w-full lg:w-1/2" label="Ngoại ngữ">
            <option :value="null">- Chọn -</option>
            <option v-for="ng in ngoaingu" :key="ng.id" :value="ng.id">{{ ng.tenng }}</option>
          </select-input>
          <select-input v-model="form.chuyenmon" :error="form.errors.chuyenmon" class="pr-6 pb-8 w-full lg:w-1/2" label="Chuyên môn">
            <option :value="null">- Chọn -</option>
            <option v-for="cm in chuyenmon" :key="cm.id" :value="cm.id">{{ cm.tencm }}</option>
          </select-input>
          <select-input v-model="form.tongiao" :error="form.errors.tongiao" class="pr-6 pb-8 w-full lg:w-1/2" label="Tôn giáo">
            <option :value="null">- Chọn -</option>
            <option v-for="tg in tongiao" :key="tg.id" :value="tg.id">{{ tg.tentg }}</option>
          </select-input>
          <select-input v-model="form.dantoc" :error="form.errors.dantoc" class="pr-6 pb-8 w-full lg:w-1/2" label="Dân tộc">
            <option :value="null">- Chọn -</option>
            <option v-for="td in dantoc" :key="td.id" :value="td.id">{{ td.tendt }}</option>
          </select-input>
          <select-input v-model="form.role" :error="form.errors.role" class="pr-6 pb-8 w-full lg:w-1/2" label="Quyền hạn">
            <option :value="null">- Chọn -</option>
            <option :value="0">Người dùng</option>
            <option :value="1">Quản lý</option>
            <option :value="2">Quản trị viên</option>
          </select-input>
          <text-input v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" type="email" label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Mật khẩu" />
          <text-input v-model="form.sdt" :error="form.errors.sdt" class="pr-6 pb-8 w-full lg:w-1/2" label="Số điện thoại" />
          <text-input v-model="form.cmnd" :error="form.errors.cmnd" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="CMND" />
          <text-input v-model="form.diachi" :error="form.errors.diachi" class="pr-6 pb-8 w-full lg:w-1/2" label="Địa chỉ" />
          <text-input v-model="form.quequan" :error="form.errors.quequan" class="pr-6 pb-8 w-full lg:w-1/2" label="Quê quán" />
          <file-input v-model="form.photo" :error="form.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Ảnh đại diện" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Tạo Mới</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
export default {
  metaInfo: { title: 'Thêm Mới Nhân Viên' },
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    phucap: Array,
    bangcap: Array,
    chuyenmon: Array,
    ngoaingu: Array,
    tongiao: Array,
    dantoc: Array
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        phucap: null,
        bangcap: null,
        ngoaingu: null,
        chuyenmon: null,
        hovaten: null,
        gioitinh: null,
        tongiao: null,
        dantoc: null,
        trangthai: null,
        role: null,
        ngaysinh: null,
        email: null,
        password: null,
        sdt: null,
        cmnd: null,
        diachi: null,
        quequan: null,
        bacluong: null,
        hesoluong: null,
        photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/nhanvien')
    },
  },
}
</script>
