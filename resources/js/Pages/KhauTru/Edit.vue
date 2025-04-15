<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('khautru')">Khẩu Trừ</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ khautru.hovaten }}
      </h1>
    </div>
    <trashed-message v-if="khautru.deleted_at" class="mb-6" @restore="restore">
      Khẩu trừ này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.mucdong" :error="form.errors.mucdong" class="pr-6 pb-8 w-full lg:w-1/1" label="Mức đóng" />
          <select-input v-model="form.loaibaohiem" :error="form.errors.loaibaohiem" class="pr-6 pb-8 w-full lg:w-1/2" label="Loại bảo hiểm">
            <option :value="null">- Chọn -</option>
            <option v-for="lbh in loaibaohiem" :key="lbh.id" :value="lbh.id">{{ lbh.tenbh }}</option>
          </select-input>
          <text-input v-model="form.ngaydong" :error="form.errors.ngaydong" class="pr-6 pb-8 w-full lg:w-1/2" type="month" label="Ngày đóng" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!khautru.deleted_at && $page.props.auth.user.role == 2" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Khẩu Trừ</button>
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
      title: `${this.khautru.hovaten}`,
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
    loaibaohiem: Array,
    khautru: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        loaibaohiem: this.khautru.loaibaohiem,
        mucdong: this.khautru.mucdong.toString(),
        ngaydong: this.khautru.ngaydong,
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('khautru.update', this.khautru.id))
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('khautru.destroy', this.khautru.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('khautru.restore', this.khautru.id))
        }
    },
  },
}
</script>
