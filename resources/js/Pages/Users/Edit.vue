<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('users')">Người Dùng</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ user.hovaten }}
      </h1>
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
      Người dùng này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="user.hovaten" class="pr-6 pb-8 w-full lg:w-1/2" label="Họ và tên" disabled/>
          <text-input :disabled="$page.props.auth.user.role == 2 ? false : true" v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
          <select-input :disabled="$page.props.auth.user.role == 2 ? false : true" v-model="form.role" :error="form.errors.role" class="pr-6 pb-8 w-full lg:w-1/2" label="Quyền hạn">
            <option :value="0">Người dùng</option>
            <option :value="1">Quản lý</option>
            <option :value="2">Quản trị viên</option>
          </select-input>
          <text-input v-model="form.password" :error="form.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Mật khẩu" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!user.deleted_at && $page.props.auth.user.role == 2 && $page.props.auth.user.id != user.id" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Người Dùng</button>
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
      title: `${this.user.hovaten}`,
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
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        email: this.user.email,
        password: null,
        role: this.user.role
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('users.update', this.user.id), {
            onSuccess: () => this.form.reset('password')
        })
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('users.destroy', this.user.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('users.restore', this.user.id))
        }
    },
  },
}
</script>
