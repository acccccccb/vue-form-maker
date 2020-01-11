<template>
    <el-container style="min-height:100%;">
        <el-container v-if="form.formData">
            <el-main style="background:#fafafa;min-height:100%;">
                <div class="formBoxDec">
                    <h2>
                        {{form.name||'表单标题'}}
                    </h2>
                    <div>{{form.description||'表单描述'}}</div>
                </div>
                <div class="formBox">
                    <el-form ref="form" label-position="top" :show-message="false" :model="form.formData">
                        <el-form-item
                                v-for="item in form.fields"
                                :key="item.name"
                                :prop="item.name"
                                class="formItem"
                                :rules="(item.required)?item.rules:[]"
                                :label="item.label||'字段名'">
                            <el-select :name="item.name" :placeholder="item.notes" v-model="form.formData[item.name]" v-if="item.type=='select_choice'">
                                <el-option v-for="options in item.choices" :key="options.value" :label="options.value" :value="options.value"></el-option>
                            </el-select>

                            <el-radio-group :name="item.name" :placeholder="item.notes" v-model="form.formData[item.name]" v-if="item.type=='single_choice'">
                                <div v-for="options in item.choices" :key="options.value"><el-radio :label="options.value">{{options.value}}</el-radio></div>
                            </el-radio-group>


                            <el-checkbox-group :name="item.name" :placeholder="item.notes" v-model="form.formData[item.name]" v-if="item.type=='multiple_choice'">
                                <div v-for="choices in item.choices" :key="choices.value"><el-checkbox :label="choices.value">{{choices.value}}</el-checkbox></div>
                            </el-checkbox-group>

                            <el-input
                                    :name="item.name"
                                    v-model="form.formData[item.name]"
                                    type="textarea"
                                    :placeholder="item.notes"
                                    :autosize="{ minRows: 5, maxRows:10}"
                                    v-if="item.type=='paragraph_text'">
                            </el-input>

                            <el-input
                                    :name="item.name"
                                    v-model="form.formData[item.name]"
                                    type="text"
                                    :placeholder="item.notes"
                                    v-if="item.type=='single_line_text'"></el-input>
                            <el-input
                                    :name="item.name"
                                    v-model="form.formData[item.name]"
                                    type="text"
                                    :placeholder="item.notes"
                                    :maxlength="11"
                                    prefix-icon="el-icon-phone"
                                    v-if="item.type=='single_line_phone'"></el-input>
                            <el-date-picker
                                    v-model="form.formData[item.name]"
                                    value-format="yyyy-MM-dd"
                                    type="date"
                                    v-if="item.type=='single_line_date'"
                                    :placeholder="item.notes">
                            </el-date-picker>
                            <el-time-picker
                                    v-model="form.formData[item.name]"
                                    value-format="HH:mm:ss"
                                    type="date"
                                    v-if="item.type=='single_line_time'"
                                    :placeholder="item.time">
                            </el-time-picker>
                            <el-rate
                                    v-if="item.type=='single_line_rate'"
                                    v-model="form.formData[item.name]">
                            </el-rate>
                            <el-input-number
                                    :name="item.name"
                                    v-if="item.type=='single_line_number'"
                                    v-model="form.formData[item.name]"
                                    :min.number="item.min"
                                    :precision.number="item.precision"
                                    :step-strictly="true"
                                    :step.number="item.step"
                                    :max.number="item.max"></el-input-number>
                        </el-form-item>
                        <el-form-item>
                            <el-button @click="submitForm" :style="'width:100%;border:none;background:'+form.submitBtnColor + ';color:'+form.submitBtnTextColor" type="primary">提交</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    import $api from '../../../api/FormMaker/FormMaker'
    export default {
        name: "FormMakerPublish",
        data:function(){
            return {
                form:{},
            }
        },
        created(){
            if(this.$route.query.id) {
                this.getById(this.$route.query.id);
            }
        },
        methods:{
            getById:function(id){
                let _this = this;
                $api.getById(id).then((res)=>{
                    _this.form = res;
                })
            },
            submitForm:function(){
                let $form = this.$refs['form'];
                $form.validate((valid)=>{
                    if(valid) {
                        let loading = this.$loading({
                            body:true,
                            fullscreen:true,
                            lock:true,
                            text:'正在提交',
                            background:'rgba(255,255,255,0.4)'
                        });
                        let params = this.form;
                        $api.submitForm(params).then((res)=>{
                           if(res.success==true) {
                               this.$message.success(res.msg);
                               if(this.form.jumpUrl && this.form.jumpUrl.indexOf('http')==0) {
                                    window.location.href = this.form.jumpUrl;
                               }
                               this.$refs['form'].resetFields();
                           } else {
                               this.$message.warning(res.msg);
                           }
                            loading.close();
                        })
                    } else {
                        this.$message.warning('表单验证失败');
                    }
                });

            },
        }
    }
</script>

<style scoped>
    @import "../../../../static/css/FormMaker.scss";
</style>