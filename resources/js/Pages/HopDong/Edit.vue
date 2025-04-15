<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('hopdong')">Hợp Đồng</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ hopdong.hovaten }}
      </h1>
    </div>
    <trashed-message v-if="hopdong.deleted_at" class="mb-6" @restore="restore">
      Hợp đồng này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <select-input v-model="form.loaihopdong" :error="form.errors.loaihopdong" class="pr-6 pb-8 w-full lg:w-1/1" label="Loại hợp đồng">
            <option :value="null">- Chọn -</option>
            <option :value="0">Thử việc</option>
            <option :value="1">Chính thức</option>
          </select-input>
          <text-input v-model="form.ngaybd" :error="form.errors.ngaybd" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày bắt đầu" />
          <text-input v-model="form.ngaykt" :error="form.errors.ngaykt" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày kết thúc" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!hopdong.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Hợp Đồng</button>
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
      title: `${this.hopdong.hovaten}`,
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
    hopdong: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        loaihopdong: this.hopdong.loaihopdong,
        ngaybd: this.hopdong.ngaybd,
        ngaykt: this.hopdong.ngaykt,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('hopdong.update', this.hopdong.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('hopdong.destroy', this.hopdong.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('hopdong.restore', this.hopdong.id))
        }
    },
  },
}
</script>
