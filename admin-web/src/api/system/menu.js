import { request } from '@/utils/request.js'

export default {
  /**
   * 获取数据
   * @returns
   */
  getList(params = {}) {
    return request({
      url: '/admin/menu/list',
      method: 'get',
      params
    })
  },

  /**
   * 可操作菜单
   * @returns
   */
  accessMenu(params = {}) {
    return request({
      url: '/admin/role/menu-tree',
      method: 'get',
      params
    })
  },

  /**
   * 添加数据
   * @returns
   */
  save(params = {}) {
    return request({
      url: '/admin/menu/store',
      method: 'post',
      data: params
    })
  },

  /**
   * 删除数据
   * @returns
   */
  destroy(data) {
    return request({
      url: '/admin/menu/batch-delete',
      method: 'post',
      data
    })
  },

  /**
   * 更新数据
   * @returns
   */
  update(id, data = {}) {
    return request({
      url: '/admin/menu/' + id,
      method: 'put',
      data
    })
  }
}
