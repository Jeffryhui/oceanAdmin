import { request } from '@/utils/request.js'

export default {
  /**
   * 获取数据列表
   * @returns
   */
  getPageList(params = {}) {
    return request({
      url: '/admin/role/list',
      method: 'get',
      params
    })
  },

  /**
   * 通过角色获取菜单
   * @returns
   */
  getMenuByRole(id) {
    return request({
      url: '/admin/role/role-menus?id=' + id,
      method: 'get'
    })
  },

  /**
   * 通过角色获取部门
   * @returns
   */
  getDeptByRole(id) {
    return request({
      url: '/core/role/getDeptByRole?id=' + id,
      method: 'get'
    })
  },

  /**
   * 添加数据
   * @returns
   */
  save(data = {}) {
    return request({
      url: '/admin/role/store',
      method: 'post',
      data
    })
  },

  /**
   * 删除数据
   * @returns
   */
  destroy(data) {
    return request({
      url: '/admin/role/batch-delete',
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
      url: '/admin/role/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 更新菜单权限
   * @returns
   */
  updateMenuPermission(id, data) {
    return request({
      url: '/admin/role/assign-menus?id=' + id,
      method: 'post',
      data
    })
  },

  

  /**
   * 更改数据状态
   * @returns
   */
  changeStatus(params = {}) {
    return request({
      url: '/core/role/changeStatus',
      method: 'post',
      data: params
    })
  }
}
