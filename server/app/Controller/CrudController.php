<?php

namespace App\Controller;

use App\Service\IService;
use App\Utils\Response;
use Hyperf\Validation\Request\FormRequest;
class CrudController extends Controller
{

    protected FormRequest $validator;
    protected IService $service;

    public function index()
    {
        $params = $this->request->query();
        $page = $this->request->query('page', 1);
        $limit = $this->request->query('limit', 20);
        $orderField = $this->request->query('order_field', 'id');
        $orderType = $this->request->query('order_type', 'desc');
        $list = $this->service->getList($params, $page, $limit, $orderField, $orderType);
        return Response::paginate($list);
    }

    public function store()
    {
        $data = $this->validator->validated();
        $result = $this->service->create($data);
        return Response::success(['id' => $result->id], '创建成功');
    }

    public function update(int $id)
    {
        $data = $this->validator->validated();
        $result = $this->service->update($id, $data);
        if($result){
            return Response::success(['id' => $id], '更新成功');
        }
        return Response::error('更新失败');
    }

    public function delete(int $id)
    {
        $result = $this->service->delete($id);
        if($result){
            return Response::success(['id' => $id], '删除成功');
        }
        return Response::error('删除失败');
    }

    public function batchDelete()
    {
        $ids = $this->request->post('ids',[]);
        $result = $this->service->batchDelete($ids);
        if($result){
            return Response::success(['ids' => $ids], '批量删除成功');
        }
        return Response::error('批量删除失败');
    }

    public function changeStatus()
    {
        $id = $this->request->post('id',0);
        if(empty($id)){
            return Response::error('ID不能为空');
        }
        $status = $this->request->post('status',2);
        $result = $this->service->changeStatus((int)$id, (int)$status);
        if($result){
            return Response::success(null, '状态更新成功');
        }
        return Response::error('状态更新失败');
    }
}
