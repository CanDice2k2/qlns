<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('phucap')">Phụ Cấp</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        Chỉnh Sửa
      </h1>
    </div>
    <trashed-message v-if="phucap.deleted_at" class="mb-6" @restore="restore">
      Phụ cấp này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!phucap.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Phụ Cấp</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Cập Nhật</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return {
      title: `Chỉnh Sửa Phụ Cấp`,
    }
  },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    phongban: Array,
    chucvu: Array,
    phucap: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        phongban: this.phucap.phongban,
        chucvu: this.phucap.chucvu,
        hsphucap: this.phucap.hsphucap.toString(),
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('phucap.update', this.phucap.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('phucap.destroy', this.phucap.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('phucap.restore', this.phucap.id))
        }
    },
  },
}
</script>
