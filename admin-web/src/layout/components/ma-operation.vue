<template>
  <div class="mr-2 flex justify-end lg:justify-between w-full lg:w-auto">
    <a-space class="mr-0 lg:mr-5" size="medium">

      <a-tooltip :content="$t('sys.search')">
        <a-button :shape="'circle'" @click="() => (appStore.searchOpen = true)" class="hidden lg:inline">
          <template #icon>
            <icon-search />
          </template>
        </a-button>
      </a-tooltip>

      <!--      <a-tooltip content="锁屏">-->
      <!--        <a-button :shape="'circle'" class="hidden lg:inline">-->
      <!--          <template #icon>-->
      <!--            <icon-lock />-->
      <!--          </template>-->
      <!--        </a-button>-->
      <!--      </a-tooltip>-->

      <a-tooltip :content="isFullScreen ? $t('sys.closeFullScreen') : $t('sys.fullScreen')">
        <a-button :shape="'circle'" class="hidden lg:inline" @click="screen">
          <template #icon>
            <icon-fullscreen-exit v-if="isFullScreen" />
            <icon-fullscreen v-else />
          </template>
        </a-button>
      </a-tooltip>

      <a-trigger trigger="click">
        <a-button :shape="'circle'">
          <template #icon>
            <a-badge :count="5" dot :dotStyle="{ width: '5px', height: '5px' }" v-if="messageStore.messageList.length > 0">
              <icon-notification />
            </a-badge>
            <icon-notification v-else />
          </template>
        </a-button>

        <template #content>
          <message-notification />
        </template>
      </a-trigger>

      <a-tooltip :content="$t('sys.pageSetting')">
        <a-button :shape="'circle'" @click="() => (appStore.settingOpen = true)" class="hidden lg:inline">
          <template #icon>
            <icon-settings />
          </template>
        </a-button>
      </a-tooltip>
    </a-space>
    <a-dropdown @select="handleSelect" trigger="hover">
      <a-avatar class="bg-blue-500 text-3xl avatar" style="top: -1px">
        <img :src="userStore.user && userStore.user.avatar ? $tool.showFile(userStore.user.avatar) : $url + 'avatar.jpg'" />
      </a-avatar>

      <template #content>
        <a-doption value="userCenter"><icon-user /> {{ $t('sys.userCenter') }}</a-doption>
        <a-doption value="clearCache"><icon-delete /> {{ $t('sys.clearCache') }}</a-doption>
        <a-divider style="margin: 5px 0" />
        <a-doption value="logout"><icon-poweroff /> {{ $t('sys.logout') }}</a-doption>
      </template>
    </a-dropdown>

    <a-modal v-model:visible="showLogoutModal" @ok="handleLogout" @cancel="handleLogoutCancel">
      <template #title>{{ $t('sys.logoutAlert') }}</template>
      <div>{{ $t('sys.logoutMessage') }}</div>
    </a-modal>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAppStore, useUserStore, useMessageStore } from '@/store'
import tool from '@/utils/tool'
import MessageNotification from './components/message-notification.vue'
import { useRouter } from 'vue-router'
import { Message } from '@arco-design/web-vue'
import { Push } from '@/utils/push-vue'
import { info } from '@/utils/common'
import commonApi from '@/api/common'

const messageStore = useMessageStore()
const userStore = useUserStore()
const appStore = useAppStore()
const router = useRouter()
const isFullScreen = ref(false)
const showLogoutModal = ref(false)
const handleSelect = async (name) => {
  if (name === 'userCenter') {
    router.push({ name: 'userCenter' })
  }
  if (name === 'clearCache') {
    Message.success('清除成功')
    // const res = await commonApi.clearAllCache()
    // tool.local.remove('dictData')
    // res.code === 200 && Message.success(res.message)
  }
  if (name === 'logout') {
    showLogoutModal.value = true
    document.querySelector('#app').style.filter = 'grayscale(1)'
  }
}



const handleLogout = async () => {
  await userStore.logout()
  document.querySelector('#app').style.filter = 'grayscale(0)'
  router.push({ name: 'login' })
}

const handleLogoutCancel = () => {
  document.querySelector('#app').style.filter = 'grayscale(0)'
}

const screen = () => {
  tool.screen(document.documentElement)
  isFullScreen.value = !isFullScreen.value
}

if (appStore.ws) {
  const env = import.meta.env
  const baseURL = env.VITE_APP_OPEN_PROXY === 'true' ? env.VITE_APP_PROXY_PREFIX : env.VITE_APP_BASE_URL
  const wsURL = env.VITE_APP_WS_URL ? env.VITE_APP_WS_URL : ''
  const appKey = env.VITE_APP_WS_APPKEY ? env.VITE_APP_WS_APPKEY : ''
  // 建立连接
  var connection = new Push({
    url: wsURL, // websocket地址
    app_key: appKey, // appkey
    auth: baseURL + '/plugin/webman/push/auth',
  })
  // 创建监听频道
  var user_channel = connection.subscribe('saiadmin')
  // 当saiadmin频道有message事件的消息时
  user_channel.on('message', function (message) {
    // message是消息内容
    info('新消息提示', '您有新的消息，请注意查收！')
    messageStore.messageList = message.data
  })
}
</script>
<style scoped>
:deep(.arco-avatar-text) {
  top: 1px;
}
:deep(.arco-divider-horizontal) {
  margin: 5px 0;
}
.avatar {
  cursor: pointer;
  margin-top: 6px;
}
</style>
