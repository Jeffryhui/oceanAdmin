<template>
  <div class="ma-content-block">
    <!-- CRUD 组件 -->
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :sm="8" :xs="24">
          <a-form-item field="username" label="操作用户">
            <a-input v-model="searchForm.username" placeholder="请输入操作用户" />
          </a-form-item>
        </a-col>
        <a-col :sm="8" :xs="24">
          <a-form-item field="service_name" label="业务名称">
            <a-input v-model="searchForm.service_name" placeholder="请输入业务名称" />
          </a-form-item>
        </a-col>
        <a-col :sm="8" :xs="24">
          <a-form-item field="ip" label="操作IP">
            <a-input v-model="searchForm.ip" placeholder="请输入登录IP" />
          </a-form-item>
        </a-col>
        <a-col :sm="16" :xs="24">
          <a-form-item field="create_time" label="操作时间">
            <a-range-picker v-model="searchForm.create_time" showTime style="width: 100%" />
          </a-form-item>
        </a-col>
      </template>
    </sa-table>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Message } from '@arco-design/web-vue'
import operLog from '@/api/system/operLog'

const crudRef = ref()

// 搜索表单
const searchForm = ref({
  username: '',
  service_name: '',
  ip: '',
  create_time: [],
})

// SaTable 基础配置
const options = reactive({
  api: operLog.getPageList,
  rowSelection: false,
  delete: false,
  operationColumn:false,
})

// SaTable 列配置
const columns = reactive([
  { title: '操作用户', dataIndex: 'username', width: 150 },
  { title: '业务名称', dataIndex: 'service_name', width: 150 },
  { title: '路由', dataIndex: 'router', width: 240 },
  { title: '操作IP', dataIndex: 'ip', width: 150 },
  { title: '操作地点', dataIndex: 'ip_location', width: 150 },
  { title: '操作时间', dataIndex: 'create_time', width: 180 },
])

// 页面数据初始化
const initPage = async () => {}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}

// 页面加载完成执行
onMounted(async () => {
  initPage()
  refresh()
})
</script>
