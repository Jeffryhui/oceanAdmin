<template>
  <component
    is="a-modal"
    v-model:visible="visible"
    :width="tool.getDevice() === 'mobile' ? '100%' : '800px'"
    :title="title"
    :mask-closable="false"
    :ok-loading="loading"
    @cancel="close"
    @before-ok="submit">
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="头像" field="avatar">
            <sa-upload-image v-model="formData.avatar" :rounded="true" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item field="username" label="账户">
            <a-input v-model="formData.username" :disabled="mode === 'edit'" placeholder="请输入账户" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item field="email" label="邮箱">
            <a-input v-model="formData.email" placeholder="请输入邮箱" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item field="password" label="密码">
            <a-input-password v-model="formData.password" :disabled="mode === 'edit'" placeholder="请输入密码" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item field="nickname" label="昵称">
            <a-input v-model="formData.nickname" placeholder="请输入昵称" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item field="role_ids" label="角色">
            <a-select v-model="formData.role_ids" :options="roleData" multiple placeholder="请选择角色" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item field="phone" label="手机">
            <a-input v-model="formData.phone" placeholder="请输入手机" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="状态" field="status">
            <sa-radio v-model="formData.status" dict="data_status" placeholder="请选择状态" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="备注" field="remark">
            <a-textarea v-model="formData.remark" placeholder="请输入备注" />
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '@/api/system/user'
import tool from '@/utils/tool'
import commonApi from '@/api/common'

const emit = defineEmits(['success'])

// 引用定义
const formRef = ref()
const mode = ref('')
const visible = ref(false)
const loading = ref(false)
const roleData = ref([])

let title = computed(() => {
  return '用户管理' + (mode.value == 'add' ? '-新增' : '-编辑')
})

// 表单初始值
const initialFormData = {
  id: '',
  avatar: '',
  username: '',
  nickname: '',
  password: '',
  role_ids: [],
  phone: '',
  email: '',
  status: 1,
  remark: '',
}

// 表单信息
const formData = reactive({ ...initialFormData })

// 验证规则
const rules = {
  username: [{ required: true, message: '账户不能为空' }],
  role_ids: [{ required: true, message: '角色不能为空' }],
}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据
  Object.assign(formData, initialFormData)
  formRef.value.clearValidate()
  visible.value = true
  await initPage()
  if (type == 'edit') {
    const { data } = await api.userRoles(id)
    const role = data.map((item) => item.id)
    console.log(role)
    data.role_ids = role
    data.password = ''
    setFormData(data)
  }
}

// 初始化页面数据
const initPage = async () => {
  const roleResp = await commonApi.commonGet('/admin/system-user/role-select')
  roleData.value = roleResp.data
}

// 设置数据
const setFormData = async (data) => {
  for (const key in formData) {
    if (data[key] != null && data[key] != undefined) {
      formData[key] = data[key]
    }
  }
}

// 数据保存
const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }
    let result = {}
    if (mode.value === 'add') {
      // 添加数据
      data.id = undefined
      result = await api.save(data)
    } else {
      // 修改数据
      result = await api.update(data.id, data)
    }
    if (result.code === 200) {
      Message.success('操作成功')
      emit('success')
      done(true)
    }
    // 防止连续点击提交
    setTimeout(() => {
      loading.value = false
    }, 500)
  }
  done(false)
}

// 关闭弹窗
const close = () => (visible.value = false)

defineExpose({ open, setFormData })
</script>
