<?php
/**
 * Created by PhpStorm.
 * User: futureis404
 * Date: 2018/10/21
 * Time: 2:49
 */
namespace app\formMaker\Controller;
use app\login\controller\CheckLogin;
use think\Controller;
use think\Validate;
use app\common\model\FormTable;
use app\common\model\FormField;
use think\Request;

class FormMaker extends Controller {
    public function _initialize($permission='') {
        return CheckLogin::checkLogin($permission);
    }
    public function add(Request $request){
        $uid = 11;
        $fields = $request->post('fields');
        $data = [
            "uid" => $uid,
            "name" => $request->post('name'),
            "description" => $request->post('description'),
            "jumpUrl" => $request->post('jumpUrl'),
            "submitBtnColor" => $request->post('submitBtnColor'),
            "submitBtnTextColor" => $request->post('submitBtnTextColor'),
            "fields" => (string)$fields,
            "enable" => $request->post('enable'),
            "formData" => $request->post('formData'),
            "createTime" => date("Y-m-d H:i:s"),
        ];
        $rules = [
            "uid" => ['require'],
            "name" => ['require'],
            "description" => ['require'],
            "submitBtnColor" => ['require'],
            "submitBtnTextColor" => ['require'],
            "fields" => ['require'],
            "formData" => ['require'],
            "enable" => ['require'],
        ];
        $validate = new Validate($rules);
        if ($validate->check($data)) {
            $newData = new FormTable($data);
            if($newData->save()) {
                return json_encode([
                    "success"=>true,
                    "msg"=>"保存成功",
                    "obj"=>(string)$fields
                ]);
            }
        } else {
            $result = array(
                'success' => false,
                "msg"=>$validate->getError(),
            );
            return json_encode($result);
        }
    }
    public function update(Request $request){
        $uid = 11;
        $id = $request->post('id');
        $fields = $request->post('fields');
        $data = [
            "uid" => $uid,
            "id" => $id,
            "name" => $request->post('name'),
            "description" => $request->post('description'),
            "jumpUrl" => $request->post('jumpUrl'),
            "submitBtnColor" => $request->post('submitBtnColor'),
            "submitBtnTextColor" => $request->post('submitBtnTextColor'),
            "fields" => (string)$fields,
            "enable" => $request->post('enable'),
            "formData" => $request->post('formData'),
            "createTime" => date("Y-m-d H:i:s"),
        ];
        $rules = [
            "id" => ['require'],
            "uid" => ['require'],
            "name" => ['require'],
            "description" => ['require'],
            "submitBtnColor" => ['require'],
            "submitBtnTextColor" => ['require'],
            "fields" => ['require'],
            "formData" => ['require'],
            "enable" => ['require'],
        ];
        $validate = new Validate($rules);
        if ($validate->check($data)) {
            $receiveData = FormField::
            where('formId','=',$id)
                -> where('uid','=',$uid)
                -> select();
            if(count($receiveData)==0) {
                $row = FormTable::getById($id);
                if(!empty($row)) {
                    if($row->update($data)) {
                        $result = array(
                            'success' => true,
                            "msg"=>'更新成功',
                            'obj'=>count($receiveData)
                        );
                    } else {
                        $result = array(
                            'success' => false,
                            "msg"=>'更新失败',
                        );
                    }
                    return json_encode($result);
                } else {
                    $result = array(
                        'success' => false,
                        "msg"=>'更新失败，数据不存在',
                    );
                    return json_encode($result);
                }
            } else {
                $result = array(
                    'success' => false,
                    "msg"=>'此表单已经收集了数据，请将收集的数据清空后再更新！',
                    'obj'=>count($receiveData)
                );
                return json_encode($result);
            }

        } else {
            $result = array(
                'success' => false,
                "msg"=>$validate->getError(),
            );
            return json_encode($result);
        }
    }
    public function getList(Request $request){
        $uid = 11;
        $pageSize = $request->post('pageSize')?$request->post('pageSize'):10;
        $pageNumber = $request->post('pageNo')?$request->post('pageNo'):1;
        $searchText = $request->post('searchText')?$request->post('searchText'):'';
        $sql = FormTable::
            view('FormTable','id,uid,name,description,jumpUrl,submitBtnColor,submitBtnTextColor,fields,formData,enable,createTime')
            -> where('uid','=',$uid)
            -> where('description|name','like','%'.$searchText.'%')
            -> limit(($pageNumber-1)*$pageSize,$pageSize)
            -> order('createTime desc');
        $rows = $sql->select();
        $total =  $sql->count();
        if($rows) {
            return json_encode([
                'success' => true,
                "rows"=>$rows,
                "total"=>$total
            ]);
        } else {
            return json_encode([
                'success' => false,
                "msg"=>"无结果"
            ]);
        }
    }
    public function submitForm(Request $request){
        $id = $request->post('id');
        $formData = $request->post('formData');
        $data = [
            "id"=>$id,
            "formData"=>$formData,
        ];
        $rules = [
            "id" => ['require'],
            "formData" => ['require']
        ];
        $validate = new Validate($rules);
        if ($validate->check($data)) {
            $form = FormTable::get($id);
            if(!$form) {
                return json_encode([
                    "success"=>false,
                    "msg"=>"提交失败，表单不存在"
                ]);
            }
            if($form['enable']=='false') {
                return json_encode([
                    "success"=>false,
                    "msg"=>"提交失败，表单已禁用"
                ]);
            }
            $uid = $form['uid'];
            $createTime = date("Y-m-d H:i:s");
            parse_str($formData,$formDataArr);
            $fieldKey = md5(Date('Y-d-m+H:i:s') . $formData);
            foreach ($formDataArr as $key => $value) {
                if(is_array($value)) {
                    $value = implode(",", $value);
                }
                $newData = new FormField([
                    "formId" => $id,
                    "uid" => $uid,
                    "fieldName" => $key,
                    "fieldValue" => $value,
                    "fieldKey" => $fieldKey,
                    "createTime" => $createTime,
                ]);
                if(!$newData->save()) {
                    return json_encode([
                        "success"=>false,
                        "msg"=>"保存失败"
                    ]);
                }
            }
            return json_encode([
                'success' => true,
                "msg"=>"提交成功"
            ]);
        } else {
            return json_encode([
                'success' => false,
                "msg"=>$validate->getError()
            ]);
        }
    }
    public function getDataListByFormId(Request $request){
        $uid = 11;
        $formId = $request->post('id');
        $rows = FormField::
            where('formId','=',$formId)
            -> where('uid','=',$uid)
            -> select();
        function array_group_by($arr, $key) {
            $grouped = [];
            foreach ($arr as $value) {
                $grouped[$value[$key]][] = $value;
            }
            if (func_num_args() > 2) {
                $args = func_get_args();
                foreach ($grouped as $key => $value) {
                    $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                    $grouped[$key] = call_user_func_array('array_group_by', $parms);
                }
            }
            return $grouped;
        }
        $groupArr = array_group_by($rows,'fieldKey');
        $formTabRow = FormTable::get($formId)['fields'];
        parse_str($formTabRow,$newFormTabRow);
        $formTableArr = [];
        foreach($newFormTabRow as $key=>$value) {
            $arr = explode('_',$key);
            if($arr[1]=='name') {
                $formTableArr[$value] = $newFormTabRow[$arr[0].'_label'];
            }
        }
        $newArr = [];
        foreach ($groupArr as $key=>$value) {
            foreach($value as $childKey => $childValue) {
                $childValue['fieldLabel'] = $formTableArr[$childValue['fieldName']];
            }
            array_push($newArr,$value);
        }
        return json_encode([
            'success' => true,
            "rows"=> $newArr,
            '$formTabRow'=>$formTabRow,
            '$groupArr'=>$groupArr,
        ]);
    }
    public function getById(Request $request){
        $uid = 11;
        $id = $request->post('id');
        $data = [
            "id"=>$id
        ];
        $rules = [
            "id" => ['require'],
        ];
        $validate = new Validate($rules);
        if ($validate->check($data)) {
            $rows = FormTable::get($id)
                ->where('id','=',$id)
                ->where('uid','=',$uid)
                ->view('formTable','id,uid,name,description,jumpUrl,submitBtnColor,submitBtnTextColor,fields,formData,enable,createTime')
                ->select();
            if($rows) {
                return json_encode([
                    'success' => true,
                    "obj"=>$rows[0]
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    "msg"=>"ID不存在"
                ]);
            }
        } else {
            return json_encode([
                'success' => false,
                "msg"=>$validate->getError()
            ]);
        }
    }
    public function del(Request $request) {
        $uid = 11;
        $ids = $request->post('ids');
        $arr =  explode(",",$ids);
        $map = array(
            'uid'=>$uid,
            'id'=>$arr
        );
        $map2 = array(
            'uid'=>$uid,
            'formId'=>$arr
        );
        $result = FormTable::get($map);
        $fieldData = FormField::get($map2);
        if($result) {
            $result
                -> where('id','in', $arr)
                -> delete();
            if($fieldData) {
                $fieldData
                    -> where('formId','in', $arr)
                    -> delete();
            }
            return json_encode(array(
                'success' => true,
                'msg' => "删除成功"
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'msg' => "删除失败，记录不存在"
            ));
        }
    }
    public function clear(Request $request) { // 清空数据
        $uid = 11;
        $id = $request->post('id');
        $map = array(
            'uid'=>$uid,
            'formId'=>$id
        );
        $fieldData = FormField::get($map);
        if(!empty($fieldData)) {
            $fieldData
                -> where('formId','=', $id)
                -> delete();
            return json_encode(array(
                'success' => true,
                'msg' => "数据清除成功",
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'msg' => "没有要清空的数据",
                'obj' => $fieldData
            ));
        }
    }
}