<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('phucap')">Phụ Cấp</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Thêm Mới
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <select-input v-model="form.phongban" :error="form.errors.phongban" class="pr-6 pb-8 w-full lg:w-1/2" label="Phòng ban">
            <option :value="null">- Chọn -</option>
            <option v-for="pb in phongban" :key="pb.id" :value="pb.id">{{ pb.tenpb }}</option>
          </select-input>
          <select-input v-model="form.chucvu" :error="form.errors.chucvu" class="pr-6 pb-8 w-full lg:w-1/2" label="Chức vụ">
            <option :value="null">- Chọn -</option>
            <option v-for="cv in chucvu" :key="cv.id" :value="cv.id">{{ cv.tencv }}</option>
          </select-input>
          <text-input v-model="form.hsphucap" :error="form.errors.hsphucap" class="pr-6 pb-8 w-full lg:w-1/1" label="Hệ số phụ cấp" />
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
  metaInfo: { title: 'Thêm Mới Phụ Cấp' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    phongban: Array,
    chucvu: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        phongban: null,
        chucvu: null,
        hsphucap: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/phucap')
    },
  },
}
</script>
