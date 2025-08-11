import { request } from '@/utils/request.js'

/**
 * 定时任务接口
 */
export default {
  /**
   * 数据列表
   * @returns
   */
  getPageList(params = {}) {
    return request({
      url: '/admin/crontab/list',
      method: 'get',
      params
    })
  },

  /**
   * 日志列表
   * @returns
   */
  getLogPageList(params = {}) {
    return request({
      url: '/admin/crontab/log-list',
      method: 'get',
      params
    })
  },

  /**
   * 删除定时任务日志
   * @returns
   */
  deleteLog(data) {
    return request({
      url: '/admin/crontab/batch-delete-logs',
      method: 'post',
      data
    })
  },

  /**
   * 立刻执行一次定时任务
   * @returns
   */
  run(data = {}) {
    return request({
      url: '/admin/crontab/run',
      method: 'post',
      data
    })
  },

  /**
   * 读取数据
   * @returns
   */
  read(id) {
    return request({
      url: '/tool/crontab/read?id=' + id,
      method: 'get'
    })
  },

  /**
   * 添加
   * @returns
   */
  save(data = {}) {
    return request({
      url: '/admin/crontab/store',
      method: 'post',
      data
    })
  },

  /**
   * 删除
   * @returns
   */
  destroy(data) {
    return request({
      url: '/admin/crontab/batch-delete',
      method: 'post',
      data
    })
  },

  /**
   * 更新数据
   * @returns
   */
  update(id, params = {}) {
    return request({
      url: '/admin/crontab/' + id,
      method: 'put',
      data: params
    })
  },

  /**
   * 更改状态
   * @returns
   */
  changeStatus(data = {}) {
    return request({
      url: '/admin/crontab/change-status',
      method: 'post',
      data
    })
  }
}
