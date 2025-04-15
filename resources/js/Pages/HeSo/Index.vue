<template>
  <div>
    <div class="flex justify-start max-w-3xl">
    <h1 class="mb-8 font-bold text-3xl">Hệ Số</h1>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.luongcb" :error="form.errors.luongcb" class="pr-6 pb-8 w-full lg:w-1/1" label="Lương cơ bản" />
          <text-input v-model="form.bac1" :error="form.errors.bac1" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 1" />
          <text-input v-model="form.bac2" :error="form.errors.bac2" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 2" />
          <text-input v-model="form.bac3" :error="form.errors.bac3" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 3" />
          <text-input v-model="form.bac4" :error="form.errors.bac4" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 4" />
          <text-input v-model="form.bac5" :error="form.errors.bac5" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 5" />
          <text-input v-model="form.bac6" :error="form.errors.bac6" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 6" />
          <text-input v-model="form.bac7" :error="form.errors.bac7" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 7" />
          <text-input v-model="form.bac8" :error="form.errors.bac8" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 8" />
          <text-input v-model="form.bac9" :error="form.errors.bac9" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 9" />
          <text-input v-model="form.bac10" :error="form.errors.bac10" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số bậc 10" />
       </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
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
      title: `Hệ Số`,
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
    heso: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        luongcb: this.heso.luongcb.toString(),
        bac1: this.heso.bac1.toString(),
        bac2: this.heso.bac2.toString(),
        bac3: this.heso.bac3.toString(),
        bac4: this.heso.bac4.toString(),
        bac5: this.heso.bac5.toString(),
        bac6: this.heso.bac6.toString(),
        bac7: this.heso.bac7.toString(),
        bac8: this.heso.bac8.toString(),
        bac9: this.heso.bac9.toString(),
        bac10: this.heso.bac10.toString()
      })
    }
  },
  methods: {
    update() {
        this.form.post(this.route('heso.update'))
    }
  },
}
</script>
