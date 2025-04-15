<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien')">Nhân Viên</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien.edit', nhanvien.id)">{{ nhanvien.hovaten }}</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Bảo Hiểm
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.maso" :error="form.errors.maso" class="pr-6 pb-8 w-full lg:w-1/2" label="Mã số" />
          <select-input v-model="form.loaibaohiem" :error="form.errors.loaibaohiem" class="pr-6 pb-8 w-full lg:w-1/2" label="Loại bảo hiểm">
            <option :value="null">- Chọn -</option>
            <option v-for="lbh in loaibaohiem" :key="lbh.id" :value="lbh.id">{{ lbh.tenbh }}</option>
          </select-input>
          <text-input v-model="form.noicap" :error="form.errors.noicap" class="pr-6 pb-8 w-full lg:w-1/2" label="Nơi cấp" />
          <text-input v-model="form.ngaycap" :error="form.errors.ngaycap" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày cấp" />
          <text-input v-model="form.ngayhethan" :error="form.errors.ngayhethan" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày hết hạn" />
          <select-input v-model="form.khautru" :error="form.errors.khautru" class="pr-6 pb-8 w-full lg:w-1/2" label="Có tính vào lương tháng này không?">
            <option :value="null">- Chọn -</option>
            <option :value="0">Không</option>
            <option :value="1">Có</option>
          </select-input>
          <text-input v-model="form.mucdong" :error="form.errors.mucdong" class="pr-6 pb-8 w-full lg:w-1/1" label="Mức đóng" />
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
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
export default {
  metaInfo: { title: 'Bảo Hiểm Nhân Viên' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    loaibaohiem: Array,
    nhanvien: Object
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        maso: null,
        loaibaohiem: null,
        noicap: null,
        ngaycap: null,
        ngayhethan: null,
        mucdong: null,
        khautru: null
      }),
    }
  },
  methods: {
    store() {
        this.form.post(this.route('baohiem.store', this.nhanvien.id))
    },
  },
}
</script>
