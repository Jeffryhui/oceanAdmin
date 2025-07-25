<template>
  <div class="block">
    <div class="user-header rounded-sm text-center">
      <div class="pt-3 mx-auto avatar-box">
        <sa-upload-image v-model="userInfo.avatar" rounded />
      </div>
      <div>
        <a-tag size="large" class="mt-3 rounded-full tag-primary">
          {{ (userStore.user && userStore.user.nickname) || (userStore.user && userStore.user.username) }}
        </a-tag>
      </div>
    </div>

    <a-layout-content class="block lg:flex lg:justify-between">
      <div class="ma-content-block w-full lg:w-6/12 mt-3 p-4">
        <a-tabs type="rounded">
          <a-tab-pane key="info" title="个人资料">
            <user-infomation />
          </a-tab-pane>
          <a-tab-pane key="safe" title="安全设置">
            <modify-password />
          </a-tab-pane>
        </a-tabs>
      </div>
      <div class="ma-content-block w-full lg:w-6/12 mt-3 p-4 ml-0 lg:ml-3">
        <a-tabs type="rounded">
          <a-tab-pane key="login-log" title="登录日志">
            <a-timeline class="pl-5 mt-3" v-if="loginLogList && loginLogList.length">
              <a-timeline-item :label="`地理位置；${item.ip_location}，操作系统：${item.os}`" v-for="(item, idx) in loginLogList" :key="idx">
                您于 {{ item.login_time }} 登录系统，{{ item.message }}
              </a-timeline-item>
            </a-timeline>
            <a-empty v-else />
          </a-tab-pane>
          <a-tab-pane key="operation-log" title="操作日志">
            <a-timeline class="pl-5 mt-3" v-if="operationLogList && operationLogList.length">
              <a-timeline-item
                :label="`地理位置；${item.ip_location}，方式：${item.method}，路由：${item.router}`"
                v-for="(item, idx) in operationLogList"
                :key="idx">
                您于 {{ item.create_time }} 执行了 {{ item.service_name }}
              </a-timeline-item>
            </a-timeline>
            <a-empty v-else />
          </a-tab-pane>
        </a-tabs>
      </div>
    </a-layout-content>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import { useUserStore } from '@/store'
import { Message } from '@arco-design/web-vue'
import user from '@/api/system/user'
import userApi from '@/api/system/user'

import ModifyPassword from './components/modifyPassword.vue'
import UserInfomation from './components/userInfomation.vue'

const userStore = useUserStore()
const userInfo = reactive({
  ...userStore.user,
})

const loginLogList = ref([])
const operationLogList = ref([])

const requestParams = reactive({
  limit: 5,
})

onMounted(() => {
  userApi.getUserLoginLog(Object.assign(requestParams, { page: 1})).then((res) => {
    loginLogList.value = res.data.data
  })

  userApi.getUserOperationLog(Object.assign(requestParams, { page: 1 })).then((res) => {
    operationLogList.value = res.data.data
  })
})

userInfo.avatar = userStore?.user?.avatar ?? undefined

watch(
  () => userInfo.avatar,
  async (newAvatar) => {
    if (newAvatar) {
      const response = await user.updateInfo({ avatar: newAvatar })
      if (response.code === 200) {
        Message.success('头像修改成功')
        userStore.user.avatar = newAvatar
      }
    }
  }
)
</script>
<script>
export default { name: 'userCenter' }
</script>

<style scoped>
.avatar-box {
  width: 130px;
}
.user-header {
  width: 100%;
  height: 200px;
  background: url('@/assets/userBanner.jpg') no-repeat;
  background-size: cover;
}
</style>
