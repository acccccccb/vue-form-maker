import $http from '../../api/request'
import qs from 'qs'
export default {
    getList:function(formData){
        let params = new URLSearchParams(formData);
        return $http.post('admin/formMaker/getList',params).then((res)=>{
            return res;
        })
    },
    submitForm:function(formData){
        let params = new URLSearchParams({
            id:formData.id,
            formData:qs.stringify(formData.formData, {arrayFormat: 'indices', allowDots: true})
        });
        return $http.post('admin/formMaker/submitForm',params).then((res)=>{
            return res;
        })
    },
    add:function(formData){
        if(formData.id) {
            delete formData.id;
        }
        formData.fields = qs.stringify(formData.fields, {arrayFormat: 'indices', allowDots: true});
        formData.formData = qs.stringify(formData.formData, {arrayFormat: 'indices', allowDots: true});
        let params = new URLSearchParams(formData);
        return $http.post('admin/formMaker/add',params).then((res)=>{
            return res;
        })
    },
    clear:function(id){
        let params = new URLSearchParams({"id":id});
        return $http.post('admin/formMaker/clear',params).then((res)=>{
            return res;
        })
    },
    update:function(formData){
        formData.fields = qs.stringify(formData.fields, {arrayFormat: 'indices', allowDots: true});
        formData.formData = qs.stringify(formData.formData, {arrayFormat: 'indices', allowDots: true});
        let params = new URLSearchParams(formData);
        return $http.post('admin/formMaker/update',params).then((res)=>{
            return res;
        })
    },
    getById:function(id){
        let params = new URLSearchParams({"id":id});
        return $http.post('admin/formMaker/getById',params).then((res)=>{
            let result = {...res.obj};
            result.fields = qs.parse(res.obj.fields, {arrayFormat: 'indices', allowDots: true});
            result.formData = qs.parse(res.obj.formData, {arrayFormat: 'indices', allowDots: true});
            Object.keys(result.fields).forEach(function(key){
                let item = result.fields[key];
                result.formData[item.name] = item.value;
            });
            // result.formData[result.fields[0].name] = [];
            return result;
        })
    },
    getDataListByFormId:function(id){
        let params = new URLSearchParams({"id":id});
        return $http.post('admin/formMaker/getDataListByFormId',params).then((res)=>{
            return res;
        })
    },
    del:function(ids){
        let params = new URLSearchParams({"ids":ids});
        return $http.post('admin/formMaker/del',params).then((res)=>{
            return res;
        })
    },

}
