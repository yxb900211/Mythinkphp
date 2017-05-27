<?php
namespace Home\Controller;
use Think\Controller;
use Think\Faster;
class HomeCommonController extends Controller {
    public function __call($function_name,$argments)
    {
        $model = Faster::start($this->model);
        if (method_exists($model,$function_name)) {
            $data = call_user_func_array([$model,$function_name],[$this,$argments]);
            switch ($data['type']) {
                case 'return': return $data['data']; break;
                case 'display':
                    foreach ($data['assign'] as $key => $value) {
                        $this->assign($key,$value);
                    }
                    $this->display();
                    break;
                case 'error': $this->error($data['msg']); break;
                case 'success': $this->success($data['msg'],$data['url']); break;
                case 'ajax': $this->ajaxReturn($data['assign']); break;
            }
        }else{
            parent::__call($function_name,$argments);
        }
    }
}
