import { request } from '@/utils/request.js'

export const dictType = {
  /**
   * 获取字典类型，无分页
   * @returns
   */
  getPageList(params = {}) {
    return request({
      url: '/admin/dict-type/list',
      method: 'get',
      params
    })
  },

  /**
   * 添加字典类型
   * @returns
   */
  save(params = {}) {
    return request({
      url: '/admin/dict-type/store',
      method: 'post',
      data: params
    })
  },

  /**
   * 删除字典类型
   * @returns
   */
  destroy(data) {
    return request({
      url: '/admin/dict-type/batch-delete',
      method: 'post',
      data
    })
  },

  /**
   * 修改字典类型
   * @returns
   */
  update(id, data = {}) {
    return request({
      url: '/admin/dict-type/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 更改字典类型状态
   * @returns
   */
  changeStatus(data = {}) {
    return request({
      url: '/admin/dict-type/change-status',
      method: 'post',
      data
    })
  }
}

export const dict = {
  /**
   * 快捷查询字典
   * @param {*} params
   * @returns
   */
  // getDict(code) {
  //   return request({
  //     url: '/admin/dict-data/list?code=' + code,
  //     method: 'get'
  //   })
  // },

  /**
   * 获取字典数据分页列表
   * @returns
   */
  getPageList(params = {}) {
    return request({
      url: '/admin/dict-data/list',
      method: 'get',
      params
    })
  },

  /**
   * 添加字典数据
   * @returns
   */
  addDictData(data = {}) {
    return request({
      url: '/admin/dict-data/store',
      method: 'post',
      data
    })
  },

  /**
   * 删除字典数据
   * @returns
   */
  destroyDictData(data) {
    return request({
      url: '/admin/dict-data/batch-delete',
      method: 'post',
      data
    })
  },

  /**
   * 更新字典数据
   * @returns
   */
  editDictData(id, data = {}) {
    return request({
      url: '/admin/dict-data/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 更改字典状态
   * @returns
   */
  changeStatus(data = {}) {
    return request({
      url: '/admin/dict-data/change-status',
      method: 'post',
      data
    })
  }
}
