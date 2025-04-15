<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('loaibaohiem')">Loại Bảo Hiểm</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.tenbh }}
      </h1>
    </div>
    <trashed-message v-if="loaibaohiem.deleted_at" class="mb-6" @restore="restore">
      Loại bảo hiểm này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.tenbh" :error="form.errors.tenbh" class="pr-6 pb-8 w-full lg:w-1/1" label="Tên loại bảo hiểm" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!loaibaohiem.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Loại Bảo Hiểm</button>
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
      title: `${this.loaibaohiem.tenbh}`,
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
    loaibaohiem: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        tenbh: this.loaibaohiem.tenbh,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('loaibaohiem.update', this.loaibaohiem.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('loaibaohiem.destroy', this.loaibaohiem.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('loaibaohiem.restore', this.loaibaohiem.id))
        }
    },
  },
}
</script>
