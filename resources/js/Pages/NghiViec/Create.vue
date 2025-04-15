<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien')">Nhân Viên</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien.edit', nhanvien.id)">{{ nhanvien.hovaten }}</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Nghỉ Việc
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <select-input v-model="form.huongluong" :error="form.errors.huongluong" class="pr-6 pb-8 w-full lg:w-1/2" label="Hưởng lương">
            <option :value="null">- Chọn -</option>
            <option :value="0">Không</option>
            <option :value="1">Có</option>
          </select-input>
          <text-input v-model="form.lydo" :error="form.errors.lydo" class="pr-6 pb-8 w-full lg:w-1/2" label="Lý do" />
          <text-input v-model="form.ngaybd" :error="form.errors.ngaybd" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày bắt đầu" />
          <text-input v-model="form.ngaykt" :error="form.errors.ngaykt" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày kết thúc" />
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
  metaInfo: { title: 'Nghỉ Việc Nhân Viên' },
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
        huongluong: null,
        lydo: null,
        ngaybd: null,
        ngaykt: null,
      }),
    }
  },
  methods: {
    store() {
        this.form.post(this.route('nghiviec.store', this.nhanvien.id))
    },
  },
}
</script>
