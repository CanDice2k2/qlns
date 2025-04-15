<template>
  <div>
    <div class="mb-8 flex justify-between items-center">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('nhanvien')">Nhân Viên</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.hovaten }}
      </h1>
      <div>
        <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('thuongphat.create', nhanvien.id)">
            <span>Thưởng Phạt</span>
        </inertia-link>
        <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('ungluong.create', nhanvien.id)">
            <span>Ứng Lương</span>
        </inertia-link>
        <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('nhanluong.create', nhanvien.id)">
            <span>Nhận Lương</span>
        </inertia-link>
        <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('nghiviec.create', nhanvien.id)">
            <span>Nghỉ Việc</span>
        </inertia-link>
        <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('chamcong', { nhanvien: nhanvien.id })">
            <span>Chấm Công</span>
        </inertia-link>
      </div>
    </div>
    <trashed-message v-if="nhanvien.deleted_at" class="mb-6" @restore="restore">
      Nhân viên này đã bị xoá.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.hovaten" :error="form.errors.hovaten" class="pr-6 pb-8 w-full lg:w-1/2" label="Họ và tên" />
          <select-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.gioitinh" :error="form.errors.gioitinh" class="pr-6 pb-8 w-full lg:w-1/2" label="Giới tính">
            <option :value="null">- Chọn --</option>
            <option :value="0">Nam</option>
            <option :value="1">Nữ</option>
          </select-input>
          <select-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.trangthai" :error="form.errors.trangthai" class="pr-6 pb-8 w-full lg:w-1/2" label="Trạng thái làm việc">
            <option :value="null">- Chọn -</option>
            <option :value="0">Đã nghĩ việc</option>
            <option :value="1">Đang làm việc</option>
          </select-input>
          <text-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.ngaysinh" :error="form.errors.ngaysinh" class="pr-6 pb-8 w-full lg:w-1/2" type="date" label="Ngày sinh" />
          <text-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.hesoluong" :error="form.errors.hesoluong" class="pr-6 pb-8 w-full lg:w-1/2" label="Hệ số lương" />
          <select-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.phucap" :error="form.errors.phucap" class="pr-6 pb-8 w-full lg:w-1/2" label="Phòng ban -> chức vụ">
            <option :value="null">- Chọn -</option>
            <option v-for="pc in phucap" :key="pc.id" :value="pc.id">{{ pc.tenpb }} -> {{ pc.tencv }}</option>
          </select-input>
          <select-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.bacluong" :error="form.errors.bacluong" class="pr-6 pb-8 w-full lg:w-1/2" label="Bậc lương">
            <option :value="null">- Chọn -</option>
            <option :value="1">Bậc 1</option>
            <option :value="2">Bậc 2</option>
            <option :value="3">Bậc 3</option>
            <option :value="4">Bậc 4</option>
            <option :value="5">Bậc 5</option>
            <option :value="6">Bậc 6</option>
            <option :value="7">Bậc 7</option>
            <option :value="8">Bậc 8</option>
            <option :value="9">Bậc 9</option>
            <option :value="10">Bậc 10</option>
          </select-input>
          <select-input v-model="form.bangcap" :error="form.errors.bangcap" class="pr-6 pb-8 w-full lg:w-1/2" label="Bằng cấp">
            <option :value="null">- Chọn -</option>
            <option v-for="bc in bangcap" :key="bc.id" :value="bc.id">{{ bc.tenbc }}</option>
          </select-input>
          <select-input v-model="form.ngoaingu" :error="form.errors.ngoaingu" class="pr-6 pb-8 w-full lg:w-1/2" label="Ngoại ngữ">
            <option :value="null">- Chọn -</option>
            <option v-for="ng in ngoaingu" :key="ng.id" :value="ng.id">{{ ng.tenng }}</option>
          </select-input>
          <select-input v-model="form.chuyenmon" :error="form.errors.chuyenmon" class="pr-6 pb-8 w-full lg:w-1/2" label="Chuyên môn">
            <option :value="null">- Chọn -</option>
            <option v-for="cm in chuyenmon" :key="cm.id" :value="cm.id">{{ cm.tencm }}</option>
          </select-input>
          <select-input v-model="form.tongiao" :error="form.errors.tongiao" class="pr-6 pb-8 w-full lg:w-1/2" label="Tôn giáo">
            <option :value="null">- Chọn -</option>
            <option v-for="tg in tongiao" :key="tg.id" :value="tg.id">{{ tg.tentg }}</option>
          </select-input>
          <select-input v-model="form.dantoc" :error="form.errors.dantoc" class="pr-6 pb-8 w-full lg:w-1/2" label="Dân tộc">
            <option :value="null">- Chọn -</option>
            <option v-for="td in dantoc" :key="td.id" :value="td.id">{{ td.tendt }}</option>
          </select-input>
          <text-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.sdt" :error="form.errors.sdt" class="pr-6 pb-8 w-full lg:w-1/2" label="Số điện thoại" />
          <text-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.cmnd" :error="form.errors.cmnd" class="pr-6 pb-8 w-full lg:w-1/2" type="number" label="CMND" />
          <text-input v-model="form.diachi" :error="form.errors.diachi" class="pr-6 pb-8 w-full lg:w-1/2" label="Địa chỉ" />
          <text-input v-model="form.quequan" :error="form.errors.quequan" class="pr-6 pb-8 w-full lg:w-1/2" label="Quê quán" />
          <file-input :disabled="$page.props.auth.user.role > 0 ? false : true" v-model="form.photo" :error="form.errors.photo" class="pr-6 pb-8 w-full lg:w-1/1" type="file" accept="image/*" label="Ảnh đại diện" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!nhanvien.deleted_at && $page.props.auth.user.role == 2 && $page.props.auth.user.id != nhanvien.user_id" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xoá Nhân Viên</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Cập Nhật</loading-button>
        </div>
      </form>
    </div>
    <div class="mt-12 flex justify-between items-center">
      <h1 class="font-bold text-3xl">Bảo Hiểm</h1>
      <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('baohiem.create', nhanvien.id)">
        <span>Thêm Mới</span>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Mã số</th>
          <th class="px-6 pt-6 pb-4">Loại bảo hiểm</th>
          <th class="px-6 pt-6 pb-4">Ngày cấp</th>
          <th class="px-6 pt-6 pb-4">Ngày hết hạn</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Mức đóng</th>
        </tr>
        <tr v-for="bh in baohiem" :key="bh.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('baohiem.edit', bh.id)">
              {{ bh.maso }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('baohiem.edit', bh.id)" tabindex="-1">
              {{ bh.tenbh }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('baohiem.edit', bh.id)" tabindex="-1">
              {{ bh.ngaycap }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('baohiem.edit', bh.id)" tabindex="-1">
              {{ bh.ngayhethan }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('baohiem.edit', bh.id)" tabindex="-1">
              {{ bh.mucdong }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('baohiem.edit', bh.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="baohiem.length === 0">
          <td class="border-t px-6 py-4" colspan="5">Chưa có bảo hiểm nào cả.</td>
        </tr>
      </table>
    </div>
    <div v-else class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Mã số</th>
          <th class="px-6 pt-6 pb-4">Loại bảo hiểm</th>
          <th class="px-6 pt-6 pb-4">Ngày cấp</th>
          <th class="px-6 pt-6 pb-4">Ngày hết hạn</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Mức đóng</th>
        </tr>
        <tr v-for="bh in baohiem" :key="bh.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" href="#">
              {{ bh.maso }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" href="#" tabindex="-1">
              {{ bh.tenbh }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" href="#" tabindex="-1">
              {{ bh.ngaycap }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" href="#" tabindex="-1">
              {{ bh.ngayhethan }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" href="#" tabindex="-1">
              {{ bh.mucdong }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" href="#" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="baohiem.length === 0">
          <td class="border-t px-6 py-4" colspan="5">Chưa có bảo hiểm nào cả.</td>
        </tr>
      </table>
    </div>
    <div class="mt-12 flex justify-between items-center">
      <h1 class="font-bold text-3xl">Hợp Đồng</h1>
      <inertia-link v-if="$page.props.auth.user.role > 0" class="btn-indigo" :href="route('hopdong.create', nhanvien.id)">
        <span>Thêm Mới</span>
      </inertia-link>
    </div>
    <div v-if="$page.props.auth.user.role > 0" class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Mã hợp đồng</th>
          <th class="px-6 pt-6 pb-4">Loại hợp đồng</th>
          <th class="px-6 pt-6 pb-4">Ngày bắt đầu</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Ngày kết thúc</th>
        </tr>
        <tr v-for="hd in hopdong" :key="hd.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('hopdong.edit', hd.id)">
              {{ hd.mahd }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('hopdong.edit', hd.id)">
              {{ hd.loaihopdong ? 'Hợp đồng chính thức' : 'Hợp đồng thử việc' }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('hopdong.edit', hd.id)">
              {{ hd.ngaybd }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('hopdong.edit', hd.id)">
              {{ hd.ngaykt }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('hopdong.edit', hd.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="hopdong.length === 0">
          <td class="border-t px-6 py-4" colspan="4">Chưa có hợp đồng nào cả.</td>
        </tr>
      </table>
    </div>
    <div v-else class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Mã hợp đồng</th>
          <th class="px-6 pt-6 pb-4">Loại hợp đồng</th>
          <th class="px-6 pt-6 pb-4">Ngày bắt đầu</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Ngày kết thúc</th>
        </tr>
        <tr v-for="hd in hopdong" :key="hd.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" href="#">
              {{ hd.mahd }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" href="#">
              {{ hd.loaihopdong ? 'Hợp đồng chính thức' : 'Hợp đồng thử việc' }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" href="#">
              {{ hd.ngaybd }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" href="#">
              {{ hd.ngaykt }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" href="#" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="hopdong.length === 0">
          <td class="border-t px-6 py-4" colspan="4">Chưa có hợp đồng nào cả.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return {
      title: `${this.nhanvien.hovaten}`,
    }
  },
  components: {
    Icon,
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    phucap: Array,
    bangcap: Array,
    chuyenmon: Array,
    ngoaingu: Array,
    tongiao: Array,
    dantoc: Array,
    nhanvien: Object,
    baohiem: Array,
    hopdong: Array
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        phucap: this.nhanvien.phucap,
        bangcap: this.nhanvien.bangcap,
        ngoaingu: this.nhanvien.ngoaingu,
        chuyenmon: this.nhanvien.chuyenmon,
        hovaten: this.nhanvien.hovaten,
        gioitinh: this.nhanvien.gioitinh,
        tongiao: this.nhanvien.tongiao,
        dantoc: this.nhanvien.dantoc,
        trangthai: this.nhanvien.trangthai,
        ngaysinh: this.nhanvien.ngaysinh,
        sdt: this.nhanvien.sdt,
        cmnd: this.nhanvien.cmnd,
        diachi: this.nhanvien.diachi,
        quequan: this.nhanvien.quequan,
        bacluong: this.nhanvien.bacluong,
        hesoluong: this.nhanvien.hesoluong.toString(),
        photo: null
      })
    }
  },
  methods: {
    update() {
        this.form.post('/nhanvien/' + this.nhanvien.id, {
            onSuccess: () => this.form.reset('password', 'photo')
        })
    },
    destroy() {
        if (confirm('Bạn có chắc chắn muốn xoá không?')) {
            this.$inertia.delete(this.route('nhanvien.destroy', this.nhanvien.id))
        }
    },
    restore() {
        if (confirm('Bạn có chắc chắn muốn khôi phục không?')) {
            this.$inertia.put(this.route('nhanvien.restore', this.nhanvien.id))
        }
    },
  },
}
</script>
