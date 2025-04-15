<template>
  <div>
    <div class="mb-8 flex justify-between items-center">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien')">Nhân Viên</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien.edit', nhanvien.id)">{{ nhanvien.hovaten }}</inertia-link>
        <span class="text-indigo-400 font-medium">/</span> Nhận Lương
      </h1>
      <div>
         <button @click="TinhLuong" class="flex items-center btn-indigo" type="submit">Tính Lương</button>
      </div>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.ngaynhan" :error="form.errors.ngaynhan" class="pr-6 pb-8 w-full lg:w-1/2" type="month" label="Tháng nhận" />
          <text-input v-model="form.ngaycongchuan" :error="form.errors.ngaycongchuan" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày công chuẩn" />
          <text-input v-model="form.heso" :error="form.errors.heso" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số lương" disabled/>
          <text-input v-model="form.hsphucap" :error="form.errors.hsphucap" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số phụ cấp" disabled/>
          <text-input v-model="form.khautru" :error="form.errors.khautru" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Khẩu trừ" disabled/>
          <text-input v-model="form.luongcb" :error="form.errors.luongcb" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Lương cơ bản" disabled/>
          <text-input v-model="form.phucap" :error="form.errors.phucap" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Phụ cấp" disabled/>
          <text-input v-model="form.mucluong" :error="form.errors.mucluong" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Mức lương" disabled/>
          <text-input v-model="form.ngaycong" :error="form.errors.ngaycong" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày công" disabled/>
          <text-input v-model="form.nghihl" :error="form.errors.nghihl" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày nghỉ hưởng lương" disabled/>
          <text-input v-model="form.nghikhl" :error="form.errors.nghikhl" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày nghỉ không hưởng lương" disabled/>
          <text-input v-model="form.thuong" :error="form.errors.thuong" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Tiền thưởng" disabled/>
          <text-input v-model="form.phat" :error="form.errors.phat" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Tiền phạt" disabled/>
          <text-input v-model="form.tamung" :error="form.errors.tamung" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Tạm ứng" disabled/>
          <text-input v-model="form.thuclinh" :error="form.errors.thuclinh" class="pr-6 pb-8 w-full lg:w-1/1" type="number" label="Thực lĩnh" disabled/>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Tạo Mới</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
export default {
  metaInfo: { title: 'Nhân Viên Nhận Lương' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    nhanvien: Object
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        heso: null,
        hsphucap: null,
        khautru: null,
        luongcb: null,
        mucluong: null,
        phucap: null,
        ngaycongchuan: null,
        ngaycong: null,
        nghihl: null,
        nghikhl: null,
        thuong: null,
        phat: null,
        tamung: null,
        thuclinh: null,
        ngaynhan: null,
      }),
    }
  },
  methods: {
    store() {
        this.form.post(this.route('nhanluong.store', this.nhanvien.id))
    },
    TinhLuong()
    {
        if (this.form.ngaynhan == null)
        {
            alert('Vui lòng chọn ngày nhận lương.');
            return;
        }
        if (this.form.ngaycongchuan == null)
        {
            alert('Vui lòng chọn ngày công chuẩn.');
            return;
        }
        let ngay = this.form.ngaynhan.split('-');
        axios.get('/nhanluong/tinhluong?id=' + this.nhanvien.id + '&thang=' + ngay[1] + '&nam=' + ngay[0] + '&ngaycong=' + this.form.ngaycongchuan).then(response => {
            console.log(response.data);
            this.form.heso = response.data.hesoluong.toString();
            this.form.hsphucap = response.data.hsphucap.toString();
            this.form.khautru = response.data.khautru.toString();
            this.form.luongcb = response.data.luongcb.toString();
            this.form.mucluong = response.data.mucluong.toString();
            this.form.phucap = response.data.phucap.toString();
            this.form.ngaycongchuan = response.data.ngaycongchuan.toString();
            this.form.ngaycong = response.data.ngaycong.toString();
            this.form.nghihl = response.data.ngaynghihl.toString();
            this.form.nghikhl = response.data.ngaynghikhl.toString();
            this.form.thuong = response.data.thuong.toString();
            this.form.phat = response.data.phat.toString();
            this.form.tamung = response.data.tamung.toString();
            this.form.thuclinh = response.data.thuclinh.toString();
            alert('Đã tính lương thành công!');
        });
    },
  },
}
</script>
