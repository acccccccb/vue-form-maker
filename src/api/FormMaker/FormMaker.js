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
        console.log(formData);
        formData.fields = qs.stringify(formData.fields, {arrayFormat: 'indices', allowDots: true});
        formData.formData = qs.stringify(formData.formData, {arrayFormat: 'indices', allowDots: true});
        console.log(typeof formData.fields);
        console.log(formData.fields);
        let params = new URLSearchParams(formData);
        return $http.post('admin/formMaker/add',params).then((res)=>{
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
                console.log(item);
                if(item.type=='multiple_choice') {
                    result.formData[item.name] = [];
                }
            });
            result.formData[result.fields[0].name] = [];
            // if(item.type=='multiple_choice') {
            //     result.formData[item.name] = [];
            // }
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
