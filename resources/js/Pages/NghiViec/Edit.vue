<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nghiviec')">Nghỉ Việc</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ nghiviec.hovaten }}
      </h1>
    </div>
    <trashed-message v-if="nghiviec.deleted_at" class="mb-6" @restore="restore">
      Nghỉ việc này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!nghiviec.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Nghỉ Việc</button>
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
      title: `${this.nghiviec.hovaten}`,
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
    nghiviec: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        huongluong: this.nghiviec.huongluong,
        lydo: this.nghiviec.lydo,
        ngaybd: this.nghiviec.ngaybd,
        ngaykt: this.nghiviec.ngaykt,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('nghiviec.update', this.nghiviec.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('nghiviec.destroy', this.nghiviec.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('nghiviec.restore', this.nghiviec.id))
        }
    },
  },
}
</script>
