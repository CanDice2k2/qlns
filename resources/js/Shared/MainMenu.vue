<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('dashboard')">
        <icon name="dashboard" class="w-4 h-4 mr-2" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Trang Chủ</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role == 2" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('heso')">
        <icon name="heso" class="w-4 h-4 mr-2" :class="isUrl('heso') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('heso') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Hệ Số</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role == 2" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('phucap')">
        <icon name="phucap" class="w-4 h-4 mr-2" :class="isUrl('phucap') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('phucap') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Phụ Cấp</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('ungluong')">
        <icon name="ungluong" class="w-4 h-4 mr-2" :class="isUrl('ungluong') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('ungluong') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Ứng Lương</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('baohiem')">
        <icon name="baohiem" class="w-4 h-4 mr-2" :class="isUrl('baohiem') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('baohiem') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Bảo Hiểm</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('hopdong')">
        <icon name="hopdong" class="w-4 h-4 mr-2" :class="isUrl('hopdong') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('hopdong') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Hợp Đồng</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('nghiviec')">
        <icon name="nghiviec" class="w-4 h-4 mr-2" :class="isUrl('nghiviec') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('nghiviec') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Nghỉ Việc</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('thuongphat')">
        <icon name="thuongphat" class="w-4 h-4 mr-2" :class="isUrl('thuongphat') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('thuongphat') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Thưởng Phạt</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('khautru')">
        <icon name="khautru" class="w-4 h-4 mr-2" :class="isUrl('khautru') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('khautru') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Khẩu Trừ</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role == 2" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('bangchamcong')">
        <icon name="chamcong" class="w-4 h-4 mr-2" :class="isUrl('bangchamcong') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('bangchamcong') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Chấm Công</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('nhanluong')">
        <icon name="nhanluong" class="w-4 h-4 mr-2" :class="isUrl('nhanluong') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('nhanluong') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Nhận Lương</div>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('nhanvien')">
        <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('nhanvien') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('nhanvien') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Nhân Viên</div>
      </inertia-link>
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter(url => currentUrl.startsWith(url)).length
    },
  },
}
</script>
