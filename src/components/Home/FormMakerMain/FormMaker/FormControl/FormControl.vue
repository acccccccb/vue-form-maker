<template>
    <div>
        <el-row :gutter="10" style="padding:20px;box-sizing:border-box;">
            <div style="margin-bottom:10px;"><strong>部件</strong></div>
            <el-col v-for="item in formControl" :key="item.label" :span="12" style="margin-bottom:10px;">
                <el-button style="width:100%;" @click="add(item.type)" type="default" size="small" plain>{{item.label}}</el-button>
            </el-col>
        </el-row>
    </div>
</template>
<script>
    export default {
        name: "FormControl",
        data(){
            return {
                formControl:{
                    single_choice:{
                        "name":"",
                        "label": "单项选择",
                        "value":null,
                        "type": "single_choice",
                        "notes": "请选择一项",
                        "private": false,
                        "validation": {},
                        "required":true,
                        "rules":[
                            { required: true, message: '请选择一项', trigger: ['blur','change'] },
                        ],
                        "choices": [
                            {
                                "name": "选项1",
                                "value": "选项1"
                            }
                        ],
                        "allow_other": false
                    },
                    select_choice:{
                        "name":"",
                        "value":null,
                        "label": "下拉选择",
                        "type": "select_choice",
                        "notes": "请选择一项",
                        "private": false,
                        "required":true,
                        "rules":[
                            { required: true, message: '请选择一项', trigger: ['blur','change'] },
                        ],
                        "validation": {},
                        "choices": [
                            {
                                "name": "选项1",
                                "value": "选项1"
                            },
                        ],
                        "allow_other": false
                    },
                    multiple_choice:{
                        "name":"",
                        "label": "多项选择",
                        "value":[0],
                        "type": "multiple_choice",
                        "notes": "请选择",
                        "private": false,
                        "validation": {},
                        "required":true,
                        "rules":[
                            { required: true, message: '请选择', trigger: ['blur','change'] },
                        ],
                        "choices": [
                            {
                                "name": "选项1",
                                "value": "选项1"
                            },
                        ],
                        "allow_other": false
                    },
                    paragraph_text:{
                        "name":"",
                        "label": "多行文本",
                        "value":"",
                        "type": "paragraph_text",
                        "notes": "请填写",
                        "required":true,
                        "rules":[
                            { required: true, message: '请填写', trigger: ['blur','change'] },
                        ],
                        "private": false,
                        "validation": {}
                    },
                    single_line_text:{
                        "name":"",
                        "label": "单行文本",
                        "value":"",
                        "type": "single_line_text",
                        "notes": "请填写",
                        "required":true,
                        "rules":[
                            { required: true, message: '请填写', trigger: ['blur','change'] },
                        ],
                        "private": false,
                        "validation": {}
                    },
                    single_line_number:{
                        "name":"",
                        "label": "数字",
                        "value":"",
                        "type": "single_line_number",
                        "notes": "请填写",
                        "max":10,
                        "min":0,
                        "precision":0,
                        "step":1,
                        "required":true,
                        "rules":[
                            { required: true, message: '请填写', trigger: ['blur','change'] },
                        ],
                        "private": false,
                        "validation": {}
                    },
                    single_line_phone:{
                        "name":"",
                        "label": "手机号",
                        "value":"",
                        "type": "single_line_phone",
                        "notes": "请填写手机号",
                        "private": false,
                        "required":true,
                        "rules":[
                            { required: true, message: '请填写手机号', trigger: ['blur','change'] },
                            { pattern: /^1(3|4|5|6|7|8|9)\d{9}$/, message: '请填写手机号', trigger: ['blur','change'] },
                        ],
                        "validation": {}
                    },
                    single_line_date:{
                        "name":"",
                        "label": "日期",
                        "value":"",
                        "type": "single_line_date",
                        "notes": "请选择日期",
                        "private": false,
                        "required":true,
                        "rules":[
                            { required: true, message: '请选择日期', trigger: ['blur','change'] },
                        ],
                        "validation": {}
                    },
                    single_line_time:{
                        "name":"",
                        "label": "时间",
                        "value":"",
                        "type": "single_line_time",
                        "notes": "请选择时间",
                        "private": false,
                        "required":true,
                        "rules":[
                            { required: true, message: '请选择时间', trigger: ['blur','change'] },
                        ],
                        "validation": {}
                    },
                    single_line_rate:{
                        "name":"",
                        "label": "评分",
                        "value":0,
                        "type": "single_line_rate",
                        "notes": "请打分",
                        "private": false,
                        "required":true,
                        "rules":[
                            { required: true, message: '请打分', trigger: ['blur','change'] },
                        ],
                        "validation": {}
                    },
                    custom_image:{
                        "label":"自定义图片",
                        "type": "custom_image",
                    },
                }
            }
        },
        methods:{
            radomNumber:function(){
                let len = 8;
                var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
                var maxPos = $chars.length;
                var pwd = '';
                for (var i = 0; i < len; i++) {
                    pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
                }
                return pwd;
            },
            add:function($type){
                let controlData = {...this.formControl[$type]};
                controlData.name = 'field_'+ this.radomNumber();
                this.$emit('add',controlData);
                this.$message.info('添加成功');
            },
        }
    }
</script>

<style scoped>

</style>