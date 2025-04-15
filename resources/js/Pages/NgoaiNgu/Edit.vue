<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('ngoaingu')">Ngoại Ngữ</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.tenng }}
      </h1>
    </div>
    <trashed-message v-if="ngoaingu.deleted_at" class="mb-6" @restore="restore">
      Ngoại ngữ này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.tenng" :error="form.errors.tenng" class="pr-6 pb-8 w-full lg:w-1/1" label="Tên ngoại ngữ" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!ngoaingu.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Ngoại Ngữ</button>
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
      title: `${this.ngoaingu.tenng}`,
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
    ngoaingu: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        tenng: this.ngoaingu.tenng,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('ngoaingu.update', this.ngoaingu.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('ngoaingu.destroy', this.ngoaingu.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('ngoaingu.restore', this.ngoaingu.id))
        }
    },
  },
}
</script>
