<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien')">Nhân Viên</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien.edit', nhanvien.id)">{{ nhanvien.hovaten }}</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Thưởng Phạt
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <select-input v-model="form.loai" :error="form.errors.loai" class="pr-6 pb-8 w-full lg:w-1/2" label="Hình thức">
            <option :value="null">- Chọn -</option>
            <option :value="0">Phạt</option>
            <option :value="1">Thưởng</option>
          </select-input>
          <text-input v-model="form.lydo" :error="form.errors.lydo" class="pr-6 pb-8 w-full lg:w-1/2" label="Lý do" />
          <text-input v-model="form.sotien" :error="form.errors.sotien" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Số tiền" />
          <text-input v-model="form.ngayapdung" :error="form.errors.ngayapdung" class="pr-6 pb-8 w-full lg:w-1/2" type="month" label="Áp dụng cho tháng nào?" />
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
  metaInfo: { title: 'Thưởng Phạt Nhân Viên' },
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
        loai: null,
        lydo: null,
        sotien: null,
        ngayapdung: null,
      }),
    }
  },
  methods: {
    store() {
        this.form.post(this.route('thuongphat.store', this.nhanvien.id))
    },
  },
}
</script>
