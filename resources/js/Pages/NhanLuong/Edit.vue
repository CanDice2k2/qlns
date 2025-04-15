<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanluong')">Nhận Lương</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ nhanluong.hovaten }}
      </h1>
    </div>
    <trashed-message v-if="nhanluong.deleted_at" class="mb-6" @restore="restore">
      Nhận lương này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.ngaynhan" :error="form.errors.ngaynhan" class="pr-6 pb-8 w-full lg:w-1/2" type="month" label="Tháng nhận" />
          <text-input v-model="form.ngaycongchuan" :error="form.errors.ngaycongchuan" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày công chuẩn" />
          <text-input v-model="form.heso" :error="form.errors.heso" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số lương" />
          <text-input v-model="form.hsphucap" :error="form.errors.hsphucap" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số phụ cấp" />
          <text-input v-model="form.khautru" :error="form.errors.khautru" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Khẩu trừ" />
          <text-input v-model="form.luongcb" :error="form.errors.luongcb" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Lương cơ bản" />
          <text-input v-model="form.phucap" :error="form.errors.phucap" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Phụ cấp" />
          <text-input v-model="form.mucluong" :error="form.errors.mucluong" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Mức lương" />
          <text-input v-model="form.ngaycong" :error="form.errors.ngaycong" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày công" />
          <text-input v-model="form.nghihl" :error="form.errors.nghihl" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày nghỉ hưởng lương" />
          <text-input v-model="form.nghikhl" :error="form.errors.nghikhl" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Ngày nghỉ không hưởng lương" />
          <text-input v-model="form.thuong" :error="form.errors.thuong" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Tiền thưởng" />
          <text-input v-model="form.phat" :error="form.errors.phat" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Tiền phạt" />
          <text-input v-model="form.tamung" :error="form.errors.tamung" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="Tạm ứng" />
          <text-input v-model="form.thuclinh" :error="form.errors.thuclinh" class="pr-6 pb-8 w-full lg:w-1/1" type="number" label="Thực lĩnh" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!nhanluong.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Nhận Lương</button>
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
      title: `${this.nhanluong.hovaten}`,
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
    nhanluong: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        heso: this.nhanluong.heso.toString(),
        hsphucap: this.nhanluong.hsphucap.toString(),
        khautru: this.nhanluong.khautru,
        luongcb: this.nhanluong.luongcb,
        mucluong: this.nhanluong.mucluong,
        phucap: this.nhanluong.phucap,
        ngaycongchuan: this.nhanluong.ngaycongchuan,
        ngaycong: this.nhanluong.ngaycong,
        nghihl: this.nhanluong.nghihl,
        nghikhl: this.nhanluong.nghikhl,
        thuong: this.nhanluong.thuong,
        phat: this.nhanluong.phat,
        tamung: this.nhanluong.tamung,
        thuclinh: this.nhanluong.thuclinh,
        ngaynhan: this.nhanluong.ngaynhan,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('nhanluong.update', this.nhanluong.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('nhanluong.destroy', this.nhanluong.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('nhanluong.restore', this.nhanluong.id))
        }
    },
  },
}
</script>
