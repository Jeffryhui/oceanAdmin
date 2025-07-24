import { request } from '@/utils/request.js'

export default {
  /**
   * 获取文件分页列表
   * @returns
   */
  getPageList(params = {}) {
    return request({
      url: '/admin/attachment/list',
      method: 'get',
      params
    })
  },

  /**
   * 删除数据
   * @returns
   */
  destroy(data) {
    return request({
      url: '/admin/attachment/batch-delete',
      method: 'post',
      data
    })
  }
}
