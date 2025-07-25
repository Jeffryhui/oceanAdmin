<script setup>
import { reactive, ref } from 'vue'
import loginApi from '@/api/login'
import { useUserStore } from '@/store'
import { useRouter, useRoute } from 'vue-router'
import packageJson from '../../package.json'
import { useAppStore } from '@/store'

const appStore = useAppStore()
const router = useRouter()
const route = useRoute()
const captcha = ref(null)

const loading = ref(false)

let isDevelop = import.meta.env.VITE_APP_ENV === 'development'

var odata = isDevelop ? { username: 'admin', password: 'admin000', code: '',key: '' } : { username: '', password: '', code: '',key: '' }

const form = reactive(odata)

const refreshCaptcha = () => {
  form.code = ''
  loginApi.getCaptch().then((res) => {
    if (res.code === 200) {
      captcha.value = res.data.img
      form.key = res.data.key
    }
  })
}

refreshCaptcha()

const userStore = useUserStore()

const redirect = route.query.redirect ? route.query.redirect : '/'

const handleSubmit = async ({ values, errors }) => {
  if (loading.value) {
    return
  }
  loading.value = true
  if (!errors) {
    const result = await userStore.login(form)
    if (!result) {
      loading.value = false
      refreshCaptcha()
      return
    }
    router.push(redirect)
  }
  loading.value = false
}
</script>
<template>
  <div class="login-container" :style="{ background: appStore.mode === 'dark' ? '#2e2e30e3' : '' }">
    <h3 class="login-logo">
      <img src="/logo.png" alt="logo" />
      <span>{{ $title }}</span>
    </h3>

    <div class="login-width md:w-10/12 w-11/12 mx-auto flex justify-between h-full items-center">
      <div class="w-6/12 mx-auto left-panel rounded-l pl-5 pr-5 hidden md:block">
        <div class="logo">
          <span>{{ $title }}</span>
        </div>
        <div class="slogan flex justify-end">
          <span>开箱即用高性能后台管理系统</span>
        </div>
      </div>

      <div class="md:w-6/12 w-11/12 md:rounded-r mx-auto pl-5 pr-5 pb-10">
        <h2 class="mt-10 text-3xl pb-0 mb-10 login-title">账户登录</h2>
        <a-form :model="form" @submit="handleSubmit">
          <a-form-item field="username" :hide-label="true" :rules="[{ required: true, message: '请输入用户名' }]">
            <a-input v-model="form.username" class="w-full" size="large" placeholder="请输入用户名" allow-clear>
              <template #prefix><icon-user /></template>
            </a-input>
          </a-form-item>

          <a-form-item field="password" :hide-label="true" :rules="[{ required: true, message: '请输入密码' }]">
            <a-input-password v-model="form.password" placeholder="请输入密码" size="large" allow-clear>
              <template #prefix><icon-lock /></template>
            </a-input-password>
          </a-form-item>

          <a-form-item
            field="code"
            :hide-label="true"
            :rules="[
              {
                required: true,
                message: '请输入验证码',
              },
            ]">
            <a-input v-model="form.code" placeholder="请输入验证码" size="large" allow-clear>
              <template #prefix><icon-safe /></template>
              <template #append>
                <img :src="captcha" style="height: 120px; height: 36px; cursor: pointer" @click="refreshCaptcha" />
              </template>
            </a-input>
          </a-form-item>

          <a-form-item :hide-label="true" class="mt-5">
            <a-button html-type="submit" type="primary" long size="large" :loading="loading">
              登录
            </a-button>
          </a-form-item>
        </a-form>
      </div>
    </div>

    <div class="login-bg">
      <div class="fly bg-fly-circle1"></div>
      <div class="fly bg-fly-circle2"></div>
      <div class="fly bg-fly-circle3"></div>
      <div class="fly bg-fly-circle4"></div>
    </div>
  </div>
</template>

<style scoped lang="less">
.login-container {
  width: 100%;
  height: 100%;
  position: absolute;
  background: rgb(145 185 233 / 50%);
  background-size: cover;
  z-index: 3;

  .login-logo {
    width: 100%;
    height: 80px;
    font-weight: 700;
    font-size: 20px;
    line-height: 32px;
    display: flex;
    padding: 0 20px;
    align-items: center;
    color: var(--color-text-1);

    img {
      width: 45px;
      height: 45px;
      margin-right: 10px;
    }
  }

  .login-width {
    max-width: 950px;
    background-color: var(--color-bg-3);
    padding: 10px;
    height: 500px;
    position: relative;
    top: 40%;
    margin-top: -255px;
    border-radius: 10px;
    z-index: 999;
    box-shadow: 0 2px 4px 2px #00000014;
  }

  .left-panel {
    height: 491px;
    background-image: url(@/assets/login_picture.svg);
    background-repeat: no-repeat;
    background-position: center 60px;
    background-size: contain;
  }

  .logo {
    display: flex;
    margin-top: 20px;
    color: #333;
    span {
      font-size: 28px;
      margin-left: 15px;
      color: rgb(var(--primary-6));
    }
  }
  .slogan {
    font-size: 16px;
    line-height: 50px;
    color: var(--color-text-1);
  }

  .login-title {
    color: var(--color-text-1);
  }

  :deep(.arco-input-append) {
    padding: 0 !important;
  }

  .other-login {
    cursor: pointer;
  }

  .qq:hover,
  .alipay:hover {
    background: #165dff;
  }
  .wechat:hover {
    background: #0f9c02;
  }

  .weibo:hover {
    background: #f3ce2b;
  }
}

.login-bg {
  width: 100%;
  overflow: hidden;
  z-index: 1;
}

.fly {
  pointer-events: none;
  position: fixed;
  z-index: 9999;
}

.bg-fly-circle1 {
  left: 40px;
  top: 100px;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: linear-gradient(to right, rgba(var(--primary-6), 0.07), rgba(var(--primary-6), 0.04));
  animation: move-ce64e0ea 2.5s linear infinite;
}

.bg-fly-circle2 {
  left: 15%;
  bottom: 5%;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  background: linear-gradient(to right, rgba(var(--primary-6), 0.08), rgba(var(--primary-6), 0.04));
  animation: move-ce64e0ea 3s linear infinite;
}

.bg-fly-circle3 {
  right: 12%;
  top: 90px;
  width: 145px;
  height: 145px;
  border-radius: 50%;
  background: linear-gradient(to right, rgba(var(--primary-6), 0.1), rgba(var(--primary-6), 0.04));
  animation: move-ce64e0ea 2.5s linear infinite;
}

.bg-fly-circle4 {
  right: 5%;
  top: 60%;
  width: 160px;
  height: 160px;
  border-radius: 50%;
  background: linear-gradient(to right, rgba(var(--primary-6), 0.02), rgba(var(--primary-6), 0.04));
  animation: move-ce64e0ea 3.5s linear infinite;
}

@keyframes move-ce64e0ea {
  0% {
    transform: translateY(0) scale(1);
  }

  50% {
    transform: translateY(25px) scale(1.1);
  }

  to {
    transform: translateY(0) scale(1);
  }
}
</style>
