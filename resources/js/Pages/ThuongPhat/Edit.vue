<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('thuongphat')">Thưởng Phạt</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ thuongphat.hovaten }}
      </h1>
    </div>
    <trashed-message v-if="thuongphat.deleted_at" class="mb-6" @restore="restore">
      Thưởng phạt này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!thuongphat.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Thưởng Phạt</button>
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
      title: `${this.thuongphat.hovaten}`,
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
    thuongphat: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        loai: this.thuongphat.loai,
        lydo: this.thuongphat.lydo,
        sotien: this.thuongphat.sotien.toString(),
        ngayapdung: this.thuongphat.ngayapdung,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('thuongphat.update', this.thuongphat.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('thuongphat.destroy', this.thuongphat.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('thuongphat.restore', this.thuongphat.id))
        }
    },
  },
}
</script>
