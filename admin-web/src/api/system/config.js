import { request } from '@/utils/request.js'

/**
 * 系统设置接口
 */
export default {
  /**
   * 获取配置列表
   * @returns
   */
  getConfigList(params) {
    return request({
      url: '/admin/config/list',
      method: 'get',
      params
    })
  },

  /**
   * 删除配置
   * @returns
   */
  destroy(data) {
    return request({
      url: '/admin/config/batch-delete',
      method: 'post',
      data
    })
  },

  /**
   * 保存配置
   * @returns
   */
  save(data = {}) {
    return request({
      url: '/admin/config/store',
      method: 'post',
      data
    })
  },

  /**
   * 修改配置
   * @returns
   */
  update(id, data = {}) {
    return request({
      url: '/admin/config/' + id,
      method: 'put',
      data
    })
  },


  /**
   * 批量修改配置值
   * @returns
   */
  batchUpdate(data) {
    return request({
      url: '/admin/config/batch-update',
      method: 'post',
      data
    })
  },

  /**
   * 获取组列表
   * @returns
   */
  getConfigGroupList(params = {}) {
    return request({
      url: '/admin/config-group/list',
      method: 'get',
      params
    })
  },

  /**
   * 保存配置组
   * @returns
   */
  saveConfigGroup(data = {}) {
    return request({
      url: '/admin/config-group/store',
      method: 'post',
      data
    })
  },

  /**
   * 更新配置组
   * @returns
   */
  updateConfigGroup(id, data = {}) {
    return request({
      url: '/admin/config-group/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 删除配置组
   * @returns
   */
  deleteConfigGroup(data = {}) {
    return request({
      url: '/admin/config-group/batch-delete',
      method: 'post',
      data
    })
  },

  /**
   * 邮箱测试
   * @returns
   */
  testEmail(data = {}) {
    return request({
      url: '/core/configGroup/email',
      method: 'post',
      data
    })
  }
}
