<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('chamcong', { nhanvien: nhanvien.id })">Lịch Sử Chấm Công</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> {{ nhanvien.hovaten }}
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.created_at" :error="form.errors.created_at" class="pr-6 pb-8 w-full lg:w-1/1" type="date" label="Ngày công" />
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
  metaInfo: { title: 'Chấm Công Nhân Viên' },
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
        created_at: null
      }),
    }
  },
  methods: {
    store() {
        this.form.post(this.route('chamcong.store', this.nhanvien.id))
    },
  },
}
</script>
