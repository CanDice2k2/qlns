<template>
  <!-- <div class="p-6 bg-indigo-800 min-h-screen flex justify-center items-center"> -->
<div class="p-6 min-h-screen flex justify-center items-center" style="background-image: url('/images/QLNS.jpeg'); background-size: cover; background-position: center;">
    <div class="w-full max-w-md" style="opacity: 0.95;">
      <!-- <logo class="block mx-auto w-full max-w-xs fill-white" height="50" /> -->
      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Đăng nhập hệ thống!</h1>
          <div class="mx-auto mt-6 w-24 border-b-2" />
          <text-input v-model="form.email" :error="form.errors.email" class="mt-10" label="Email" type="email" autofocus autocapitalize="off" />
          <text-input v-model="form.password" :error="form.errors.password" class="mt-6" label="Password" type="password" />
          <label class="mt-6 select-none flex items-center" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">Ghi nhớ mật khẩu</span>
          </label>
        </div>
        <div class="px-10 py-4 bg-gray-100 border-t border-gray-100 flex">
          <loading-button :loading="form.processing" class="ml-auto btn-indigo" type="submit">Đăng Nhập</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  metaInfo: { title: 'Login' },
  components: {
    LoadingButton,
    Logo,
    TextInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: 'admin@email.com',
        password: '123',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.form.post(this.route('login.store'))
    },
  },
}
</script>
