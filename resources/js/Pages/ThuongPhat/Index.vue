<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Thưởng Phạt</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
        <label class="block text-gray-700">Trạng thái xoá:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option :value="null">- Chưa chọn -</option>
          <option value="only">Đã xoá</option>
          <option value="with">Tất cả</option>
        </select>
      </search-filter>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Mã nhân viên</th>
          <th class="px-6 pt-6 pb-4">Họ và tên</th>
          <th class="px-6 pt-6 pb-4">Loại</th>
          <th class="px-6 pt-6 pb-4">Số tiền</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Ngày áp dụng</th>
        </tr>
        <tr v-for="tp in thuongphat.data" :key="tp.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('thuongphat.edit', tp.id)">
              {{ tp.manv }}
              <icon v-if="tp.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2" />
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('thuongphat.edit', tp.id)">
              {{ tp.hovaten }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('thuongphat.edit', tp.id)" tabindex="-1">
              {{ tp.loai ? 'Thưởng' : 'Phạt' }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('thuongphat.edit', tp.id)" tabindex="-1">
              {{ tp.sotien }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('thuongphat.edit', tp.id)" tabindex="-1">
              {{ tp.ngayapdung }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('thuongphat.edit', tp.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="thuongphat.data.length === 0">
          <td class="border-t px-6 py-4" colspan="5">Không có ứng lương nào cả.</td>
        </tr>
      </table>
    </div>
    <pagination :links="thuongphat.links" />
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import pickBy from 'lodash/pickBy'
import SearchFilter from '@/Shared/SearchFilter'
import throttle from 'lodash/throttle'
export default {
  metaInfo: { title: 'Thưởng Phạt' },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  props: {
    thuongphat: Object,
    filters: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      handler: throttle(function() {
        let query = pickBy(this.form)
        this.$inertia.replace(this.route('thuongphat', Object.keys(query).length ? query : { remember: 'forget' }))
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
